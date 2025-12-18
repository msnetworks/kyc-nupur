<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
// use App\Models\Fitype;
use App\Models\Cases;
use App\Models\Bank;
use App\Models\BranchCode; 
use App\Models\Product;
use App\Models\FiType;
use App\Models\ApplicationType;
use App\Models\User;
use App\Models\casesFiType;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use ZipArchive;
use App\Exports\ExportCase;
use Dompdf\Dompdf;
use Intervention\Image\Facades\Image;
use App\Helpers\LogHelper;
use App\Helpers\CaseHistoryHelper;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use App\Jobs\ImportCasesJob;
 
class CasesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $user;
    public function __construct()
    {
        // Apply the admin authentication middleware
        $this->middleware('auth:admin');

        // Set the user property for authenticated users
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('admin')->user();
            return $next($request);
        });
    }
    public function index()
    {

        // Check if the logged-in admin is a "Bank" role
        if (Auth::guard('admin')->check() && in_array(Auth::guard('admin')->user()->role, ['Bank', 'Admin', 'Manager'])) {
            $cases = Cases::where("created_by", Auth::guard('admin')->user()->id)->get();
        } else {
            $cases = Cases::all();
        }

        // Fetch all banks (products)
        $products = Bank::all();
        $fitype = FiType::all();

        // Return the view with filtered data
        return view('backend.pages.cases.index', compact('cases', 'products', 'fitype'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::guard('admin')->user();
        $roles              = Role::all();
        if (Auth::guard('admin')->user()->id != 1) {
            $assignBank = explode(',', Auth::guard('admin')->user()->banks_assign);
            $banks = Bank::whereIn('id', $assignBank)->get();
            $branchcode = BranchCode::whereIn('id', $assignBank)->get();
        } else {
            $banks = Bank::all();
        }

        $fitypes            = FiType::all();
        $ApplicationTypes   = ApplicationType::where('status', '1')->get();
        $session_id         = Auth::guard('admin')->user()->id;
        $users              = User::where('admin_id', $session_id)->get();


        $fitypesFeild = '';
        $AgentsFeild = '';
        foreach ($fitypes as $key => $fitype) {
            $fitypesFeild .= '<div class="form-group col-md-6 col-sm-12 ' . $fitype['fi_code'] . '_section' . ' d-none">';
            $fitypesFeild .= '<label for="Address' . $fitype['id'] . '">' . $fitype['name'] . ' Address</label>';
            $fitypesFeild .= '<input type="text" class="form-control" name="fi_type_id[' . $key . '][address]" placeholder="Address">';

            $fitypesFeild .= '</div>';
            $fitypesFeild .= '<div class="form-group col-md-6 col-sm-12 ' . $fitype['fi_code'] . '_section' . ' d-none">';
            $fitypesFeild .= '<label for="Pincode' . $fitype['id'] . '">' . $fitype['name'] . ' Pincode</label>';
            $fitypesFeild .= '<input type="text" class="form-control" name="fi_type_id[' . $key . '][pincode]" placeholder="Pincode" pattern="[0-9]{6}" oninvalid="this.setCustomValidity(\'Please enter a valid 6-digit pincode\')" oninput="setCustomValidity(\'\')">';

            $fitypesFeild .= '</div>';
            $fitypesFeild .= '<div class="form-group col-md-6 col-sm-12 ' . $fitype['fi_code'] . '_section' . ' d-none">';
            $fitypesFeild .= '<label for="phone number' . $fitype['id'] . '">' . $fitype['name'] . ' Phone Number</label>';
            $fitypesFeild .= '<input type="text" class="form-control" name="fi_type_id[' . $key . '][phone_number]"  pattern="[0-9]{10}" oninvalid="this.setCustomValidity(\'Please enter a valid 10-digit Phone Number\')" oninput="setCustomValidity(\'\')" placeholder="Phone Number">';

            $fitypesFeild .= '</div>';
            $fitypesFeild .= '<div class="form-group col-md-6 col-sm-12 ' . $fitype['fi_code'] . '_section' . ' d-none">';
            $fitypesFeild .= '<label for="landmark' . $fitype['id'] . '">' . $fitype['name'] . ' Land Mark</label>';
            $fitypesFeild .= '<input type="text" class="form-control" name="fi_type_id[' . $key . '][landmark]" placeholder="landmark">';

            $fitypesFeild .= '</div>';
        }
        return view('backend.pages.cases.create', compact('banks', 'roles', 'fitypes', 'fitypesFeild', 'ApplicationTypes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [];
        $messages = [];
        $user = Auth::guard('admin')->user();
        if ($request['application_type'] == '1' || $request['application_type'] == '2') {
            // Validate applicant name for Applicant and Co-Applicant
            $rules['applicant_name'] = 'required|max:50';
            $messages['applicant_name.required'] = 'The applicant name is required.';
        } elseif ($request['application_type'] == '3') {
            // Validate guarantee name for Guarantor
            $rules['guarantee_name'] = 'required|max:50';
            $messages['guarantee_name.required'] = 'The guarantor name is required.';
        } elseif ($request['application_type'] == '4') {
            // Validate seller name for Seller
            $rules['seller_name'] = 'required|max:50';
            $messages['seller_name.required'] = 'The seller name is required.';
        }
        $request->validate($rules, $messages);

        // Generate reference number
        $existing_cases_count = Cases::where('branch_code', $request->branch_code)->count();
        $branchcode = BranchCode::where('id', $request->branch_code)->first();
        $automatic_number = str_pad($existing_cases_count + 1, 5, '0', STR_PAD_LEFT); // 5-digit padded number
        $reference_number = "SRM/{$branchcode->branch_code}/{$automatic_number}";
        if(!empty($request->fi_type_id)){
        // Create New cases
        $cases = new Cases();
        $cases->bank_id             = $request->bank_id;
        $cases->branch_code         = $request->branch_code;
        // $cases->aadhar_card         = $request->aadhar_card ?? '';
        $cases->tat_start           = $request->tat_start;
        $cases->tat_end             = $request->tat_end;
        $cases->bank_id             = $request->bank_id;
        $cases->product_id          = $request->product_id;
        $cases->application_type    = $request->application_type;
        if ($request->application_type == '1') {
            $cases->applicant_name      = $request->applicant_name;
        } elseif ($request->application_type == '2') {
            $cases->applicant_name      = $request->applicant_name;
            $cases->co_applicant_name   = $request->co_applicant_name;
        } elseif ($request->application_type == '3') {
            $cases->applicant_name      = $request->guarantee_name;
        } elseif ($request->application_type == '4') {
            $cases->applicant_name      = $request->seller_name;
        }
        $cases->refrence_number     = $reference_number;
        $cases->amount              = $request->amount ?? 0;
        // $cases->geo_limit           = $request->geo_limit;
        $cases->remarks             = $request->remarks;
        $cases->created_by          = Auth::guard('admin')->user()->id;
        $cases->updated_by          = Auth::guard('admin')->user()->id;
        // $cases->name         = $request->name;
        $cases->save();
        $cases_id = $cases->id;

        foreach ($request->fi_type_id as $fi_type_id) {
            if (!empty($fi_type_id['id'])) {
                $casesFiType = new casesFiType;
                $casesFiType->case_id       = $cases_id;
                if ($request->application_type == '1') {
                    $casesFiType->applicant_name      = $request->applicant_name;
                } elseif ($request->application_type == '2') {
                    $casesFiType->applicant_name      = $request->applicant_name;
                } elseif ($request->application_type == '3') {
                    $casesFiType->applicant_name      = $request->guarantee_name;
                } elseif ($request->application_type == '4') {
                    $casesFiType->applicant_name      = $request->seller_name;
                }

                $casesFiType->fi_type_id    = $fi_type_id['id'] ?? 0;
                $casesFiType->branch_code   = $request->branch_code;
                $casesFiType->tat_start     = $request->tat_start;
                $casesFiType->tat_end       = $request->tat_end;
                $mobile = 'mobile' . $fi_type_id['id'];
                $casesFiType->mobile = $request->$mobile;
                if (in_array($fi_type_id['id'], [7, 8])) {
                    $address = 'v_address' . $fi_type_id['id'];
                    $pincode = 'v_pincode' . $fi_type_id['id'];
                    $city = 'v_city' . $fi_type_id['id'];
                    $landmark = 'v_landmark' . $fi_type_id['id'];
                    $geolimit = 'geo_limit' . $fi_type_id['id'];
                    $casesFiType->address = $request->$address ?? '';
                    $casesFiType->pincode = $request->$pincode;
                    $casesFiType->city = $request->$city;
                    $casesFiType->geolimit = $request->$geolimit;
                }
                
                if (in_array($fi_type_id['id'], [12, 14, 17])) {
                    $panNumber = 'pan_number' . $fi_type_id['id'];
                    $casesFiType->pan_number = $request->$panNumber;
                }

                if (in_array($fi_type_id['id'], [12, 14])) {
                    $assessYear = 'assessment_year' . $fi_type_id['id'];
                    $casesFiType->assessment_year = $request->$assessYear;
                }

                if (in_array($fi_type_id['id'], [13])) {
                    $accountnumber = 'accountnumber' . $fi_type_id['id'];
                    $bankName = 'bank_name' . $fi_type_id['id'];
                    $casesFiType->accountnumber = $request->$accountnumber;
                    $casesFiType->bank_name = $request->$bankName;
                }

                if (in_array($fi_type_id['id'], [17])) {
                    $aadharNumber = 'aadhar_number' . $fi_type_id['id'];
                    $casesFiType->aadhar_card = $request->$aadharNumber;
                }
                $casesFiType->status       = $user->role == 'Bank' ? '1' : '0';
                $casesFiType->user_id       = $user->role == 'Bank' ? $user->default_agent_assign : '0';
                $casesFiType->save();
            }
        }

        LogHelper::logActivity('Create Case', 'User created a new case.');
        CaseHistoryHelper::logHistory($cases_id, null, null, null, 'New Case', 'Case Create', 'New Case Created');

        session()->flash('success', 'Case has been created !!');
        return redirect()->route('admin.case.index');
        
        }
        else{
            session()->flash('error', 'Please Select Case has been created !!');
        return redirect()->route('admin.case.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cases = Cases::where('id', $id)->with('getCaseFiType')->firstOrFail();
        $roles              = Role::all();
        $banks              = Bank::all();
        $fitypes            = FiType::all();
        $ApplicationTypes   = ApplicationType::all();
        $users              = User::where('admin_id', Auth::guard('admin')->user()->id)->get();

        $fi_type_ids = $fi_type_value = [];
        if ($cases->getCaseFiType()) {
            $fi_type_ids = $cases->getCaseFiType()->pluck('fi_type_id')->toArray();
            foreach ($cases->getCaseFiType as $value) {
                $fi_type_value[$value->fi_type_id] = $value;
            }
        }

        $AvailbleProduct = [];
        if ($cases->bank_id) {
            $AvailbleProduct = Product::select('bpm.id', 'bpm.bank_id', 'bpm.product_id', 'products.name', 'products.product_code')
                ->leftJoin('bank_product_mappings as bpm', 'bpm.product_id', '=', 'products.id')
                ->where('bpm.bank_id', $cases->bank_id)
                ->where('products.status', '1')
                ->get()
                ->toArray();
        }

        $fitypesFeild = '';
        $AgentsFeild = '';
        foreach ($fitypes as $key => $fitype) {
            $fitypesFeild .= '<div class="form-group col-md-6 col-sm-12 ' . $fitype['name'] . '_section' . ' d-none">';
            $fitypesFeild .= '<label for="Address' . $fitype['id'] . '">' . $fitype['name'] . ' Address</label>';

            if (isset($fi_type_value[$fitype['id']])) {
                $address = $fi_type_value[$fitype['id']]['address'];
                $fitypesFeild .= '<input type="text" class="form-control" name="fi_type_id[' . $key . '][address]" value="' . $address . '" placeholder="Address">';
            } else {
                $fitypesFeild .= '<input type="text" class="form-control" name="fi_type_id[' . $key . '][address]" value="" placeholder="Address">';
            }

            $fitypesFeild .= '</div>';
            $fitypesFeild .= '<div class="form-group col-md-6 col-sm-12 ' . $fitype['name'] . '_section' . ' d-none">';
            $fitypesFeild .= '<label for="Pincode' . $fitype['id'] . '">' . $fitype['name'] . ' Pincode</label>';

            if (isset($fi_type_value[$fitype['id']])) {
                $pincode = $fi_type_value[$fitype['id']]['pincode'];
                $fitypesFeild .= '<input type="text" class="form-control" name="fi_type_id[' . $key . '][pincode]" value="' . $pincode . '" placeholder="Pincode"  pattern="[0-9]{6}" oninvalid="this.setCustomValidity(\'Please enter a valid 6-digit pincode\')" oninput="setCustomValidity(\'\')">';
            } else {
                $fitypesFeild .= '<input type="text" class="form-control" name="fi_type_id[' . $key . '][pincode]" value="" placeholder="Pincode" pattern="[0-9]{6}" oninvalid="this.setCustomValidity(\'Please enter a valid 6-digit pincode\')" oninput="setCustomValidity(\'\')">';
            }

            $fitypesFeild .= '</div>';
            $fitypesFeild .= '<div class="form-group col-md-6 col-sm-12 ' . $fitype['name'] . '_section' . ' d-none">';
            $fitypesFeild .= '<label for="phone number' . $fitype['id'] . '">' . $fitype['name'] . ' Phone Number</label>';

            if (isset($fi_type_value[$fitype['id']])) {
                $phone = $fi_type_value[$fitype['id']]['mobile'];
                $fitypesFeild .= '<input type="text" class="form-control" name="fi_type_id[' . $key . '][phone_number]" value="' . $phone . '" placeholder="Phone Number" pattern="[0-9]{10}" oninvalid="this.setCustomValidity(\'Please enter a valid 10-digit Phone Number\')" oninput="setCustomValidity(\'\')">';
            } else {
                $fitypesFeild .= '<input type="text" class="form-control" name="fi_type_id[' . $key . '][phone_number]" value="" placeholder="Phone Number" pattern="[0-9]{10}" oninvalid="this.setCustomValidity(\'Please enter a valid 10-digit Phone Number\')" oninput="setCustomValidity(\'\')">';
            }

            $fitypesFeild .= '</div>';
            $fitypesFeild .= '<div class="form-group col-md-6 col-sm-12 ' . $fitype['name'] . '_section' . ' d-none">';
            $fitypesFeild .= '<label for="landmark' . $fitype['id'] . '">' . $fitype['name'] . ' Land Mark</label>';

            if (isset($fi_type_value[$fitype['id']])) {
                $landmark = $fi_type_value[$fitype['id']]['land_mark'];
                $fitypesFeild .= '<input type="text" class="form-control" name="fi_type_id[' . $key . '][landmark]" value="' . $landmark . '" placeholder="landmark">';
            } else {
                $fitypesFeild .= '<input type="text" class="form-control" name="fi_type_id[' . $key . '][landmark]" value="" placeholder="landmark">';
            }

            $fitypesFeild .= '</div>';
        }

        LogHelper::logActivity('Show Case', 'User show case.');

        return view('backend.pages.cases.show', compact('cases', 'banks', 'roles', 'fitypes', 'fitypesFeild', 'ApplicationTypes', 'fi_type_ids', 'AvailbleProduct'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $users              = User::where('admin_id', Auth::guard('admin')->user()->id)->get();
        $cases = Cases::where('id', $id)->with('getCaseFiType')->firstOrFail();
        $roles              = Role::all();
        $banks              = Bank::all();
        $fitypes            = FiType::all();
        $assignBank = explode(',', Auth::guard('admin')->user()->banks_assign);
        if (Auth::guard('admin')->user()->role === 'Bank') {
            $assignBranch = explode(',', Auth::guard('admin')->user()->brnch_assign);
            $branchcode = BranchCode::whereIn('id', $assignBranch)->get();
        } else {
            $branchcode = BranchCode::where('id', $cases->bank_id)->get();
        }
        $ApplicationTypes  = ApplicationType::all();

        $fi_type_ids = $fi_type_value = [];
        if ($cases->getCaseFiType()) {
            $fi_type_ids = $cases->getCaseFiType()->pluck('fi_type_id')->toArray();
            foreach ($cases->getCaseFiType as $value) {
                $fi_type_value[$value->fi_type_id] = $value;
            }
        }

        $AvailbleProduct = [];
        if ($cases->bank_id) {
            $AvailbleProduct = Product::select('bpm.id', 'bpm.bank_id', 'bpm.product_id', 'products.name', 'products.product_code')
                ->leftJoin('bank_product_mappings as bpm', 'bpm.product_id', '=', 'products.id')
                ->where('bpm.bank_id', $cases->bank_id)
                ->where('products.status', '1')
                ->get()
                ->toArray();
        }

        $fitypesFeild = '';
        $AgentsFeild = '';
        foreach ($fitypes as $key => $fitype) {
            $fitypesFeild .= '<div class="form-group col-md-6 col-sm-12 ' . $fitype['name'] . '_section' . ' d-none">';
            $fitypesFeild .= '<label for="Address' . $fitype['id'] . '">' . $fitype['name'] . ' Address</label>';

            if (isset($fi_type_value[$fitype['id']])) {
                $address = $fi_type_value[$fitype['id']]['address'];
                $fitypesFeild .= '<input type="text" class="form-control" name="fi_type_id[' . $key . '][address]" value="' . $address . '" placeholder="Address">';
            } else {
                $fitypesFeild .= '<input type="text" class="form-control" name="fi_type_id[' . $key . '][address]" value="" placeholder="Address">';
            }

            $fitypesFeild .= '</div>';
            $fitypesFeild .= '<div class="form-group col-md-6 col-sm-12 ' . $fitype['name'] . '_section' . ' d-none">';
            $fitypesFeild .= '<label for="Pincode' . $fitype['id'] . '">' . $fitype['name'] . ' Pincode</label>';

            if (isset($fi_type_value[$fitype['id']])) {
                $pincode = $fi_type_value[$fitype['id']]['pincode'];
                $fitypesFeild .= '<input type="text" class="form-control" name="fi_type_id[' . $key . '][pincode]" value="' . $pincode . '" placeholder="Pincode" pattern="[0-9]{6}" oninvalid="this.setCustomValidity(\'Please enter a valid 6-digit pincode\')" oninput="setCustomValidity(\'\')">';
            } else {
                $fitypesFeild .= '<input type="text" class="form-control" name="fi_type_id[' . $key . '][pincode]" value="" placeholder="Pincode" pattern="[0-9]{6}" oninvalid="this.setCustomValidity(\'Please enter a valid 6-digit pincode\')" oninput="setCustomValidity(\'\')">';
            }

            $fitypesFeild .= '</div>';
            $fitypesFeild .= '<div class="form-group col-md-6 col-sm-12 ' . $fitype['name'] . '_section' . ' d-none">';
            $fitypesFeild .= '<label for="phone number' . $fitype['id'] . '">' . $fitype['name'] . ' Phone Number</label>';

            if (isset($fi_type_value[$fitype['id']])) {
                $phone = $fi_type_value[$fitype['id']]['mobile'];
                $fitypesFeild .= '<input type="text" class="form-control" name="fi_type_id[' . $key . '][phone_number]" value="' . $phone . '" placeholder="Phone Number" pattern="[0-9]{10}" oninvalid="this.setCustomValidity(\'Please enter a valid 10-digit Phone Number\')" oninput="setCustomValidity(\'\')">';
            } else {
                $fitypesFeild .= '<input type="text" class="form-control" name="fi_type_id[' . $key . '][phone_number]" value="" placeholder="Phone Number" pattern="[0-9]{10}" oninvalid="this.setCustomValidity(\'Please enter a valid 10-digit Phone Number\')" oninput="setCustomValidity(\'\')">';
            }

            $fitypesFeild .= '</div>';
            $fitypesFeild .= '<div class="form-group col-md-6 col-sm-12 ' . $fitype['name'] . '_section' . ' d-none">';
            $fitypesFeild .= '<label for="landmark' . $fitype['id'] . '">' . $fitype['name'] . ' Land Mark</label>';

            if (isset($fi_type_value[$fitype['id']])) {
                $landmark = $fi_type_value[$fitype['id']]['land_mark'];
                $fitypesFeild .= '<input type="text" class="form-control" name="fi_type_id[' . $key . '][landmark]" value="' . $landmark . '" placeholder="landmark">';
            } else {
                $fitypesFeild .= '<input type="text" class="form-control" name="fi_type_id[' . $key . '][landmark]" value="" placeholder="landmark">';
            }

            $fitypesFeild .= '</div>';
        }

        return view('backend.pages.cases.edit', compact('cases', 'banks', 'branchcode', 'roles', 'fitypes', 'fitypesFeild', 'ApplicationTypes', 'fi_type_ids', 'AvailbleProduct'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $cases = Cases::findOrFail($id);
        $cases->bank_id             = $request->bank_id ??  $cases->bank_id;
        $cases->branch_code         = $request->branch_code;
        $cases->tat_start           = $request->tat_start;
        $cases->tat_end             = $request->tat_end;
        $cases->product_id          = $request->product_id ??   $cases->product_id;
        $cases->application_type    = $request->application_type ??  $cases->application_type;
        if ($request->application_type == '1') {
            $cases->applicant_name      = $request->applicant_name ?? $cases->applicant_name;
        } elseif ($request->application_type == '2') {
            $cases->applicant_name      = $request->applicant_name ?? $cases->applicant_name;
            $cases->co_applicant_name   = $request->co_applicant_name ?? $cases->co_applicant_name;
        } elseif ($request->application_type == '3') {
            $cases->applicant_name      = $request->guarantee_name ?? $cases->applicant_name;
        } elseif ($request->application_type == '4') {
            $cases->applicant_name      = $request->applicant_name ??  $cases->applicant_name;
        }
        $cases->amount              = $request->amount ??   $cases->amount;
        $cases->geo_limit           = $request->geo_limit ??   $cases->geo_limit;
        $cases->remarks             = $request->remarks ??   $cases->remarks;
        $cases->updated_by          = Auth::guard('admin')->user()->id;
        $cases->save();
        foreach ($request->fi_type_id as $fi_type_id) {
            if (!empty($fi_type_id['id'])) {
                casesFiType::updateOrInsert(
                    [
                        'case_id' => $cases->id,
                        'fi_type_id' => $fi_type_id['id']
                    ],
                    [
                        'mobile' => $fi_type_id['phone_number'],
                        'address' => $fi_type_id['address'],
                        'pincode' => $fi_type_id['pincode'],
                        'land_mark' => $fi_type_id['landmark'],
                        'branch_code'   => $request->branch_code,
                        'tat_start'     => $request->tat_start,
                        'tat_end'       => $request->tat_end,
                        'user_id' => 0
                    ]
                );
            }
        }

        session()->flash('success', 'Case has been updated !!');

        LogHelper::logActivity('Update Case', 'User update case.');
        CaseHistoryHelper::logHistory($id, null, null, null, 'New Case', 'Update Case', 'Update Case');

        return redirect()->route('admin.case.index');
    }
    public function reinitatiateCase($id)
    {

        $cases = Cases::where('id', $id)->with('getCaseFiType')->firstOrFail();
        $roles              = Role::all();
        $banks              = Bank::all();
        $fitypes            = FiType::all();
        $ApplicationTypes   = ApplicationType::all();
        $users              = User::where('admin_id', Auth::guard('admin')->user()->id)->get();

        $fi_type_ids = $fi_type_value = [];
        if ($cases->getCaseFiType()) {
            $fi_type_ids = $cases->getCaseFiType()->pluck('fi_type_id')->toArray();
            foreach ($cases->getCaseFiType as $value) {
                $fi_type_value[$value->fi_type_id] = $value;
            }
        }

        $AvailbleProduct = [];
        if ($cases->bank_id) {
            $AvailbleProduct = Product::select('bpm.id', 'bpm.bank_id', 'bpm.product_id', 'products.name', 'products.product_code')
                ->leftJoin('bank_product_mappings as bpm', 'bpm.product_id', '=', 'products.id')
                ->where('bpm.bank_id', $cases->bank_id)
                ->where('products.status', '1')
                ->get()
                ->toArray();
        }

        $fitypesFeild = '';
        $AgentsFeild = '';
        foreach ($fitypes as $key => $fitype) {
            $fitypesFeild .= '<div class="form-group col-md-6 col-sm-12 ' . $fitype['name'] . '_section' . ' d-none">';
            $fitypesFeild .= '<label for="Address' . $fitype['id'] . '">' . $fitype['name'] . ' Address</label>';

            if (isset($fi_type_value[$fitype['id']])) {
                $address = $fi_type_value[$fitype['id']]['address'];
                $fitypesFeild .= '<input type="text" class="form-control" name="fi_type_id[' . $key . '][address]" value="' . $address . '" placeholder="Address">';
            } else {
                $fitypesFeild .= '<input type="text" class="form-control" name="fi_type_id[' . $key . '][address]" value="" placeholder="Address">';
            }

            $fitypesFeild .= '</div>';
            $fitypesFeild .= '<div class="form-group col-md-6 col-sm-12 ' . $fitype['name'] . '_section' . ' d-none">';
            $fitypesFeild .= '<label for="Pincode' . $fitype['id'] . '">' . $fitype['name'] . ' Pincode</label>';

            if (isset($fi_type_value[$fitype['id']])) {
                $pincode = $fi_type_value[$fitype['id']]['pincode'];
                $fitypesFeild .= '<input type="text" class="form-control" name="fi_type_id[' . $key . '][pincode]" value="' . $pincode . '" placeholder="Pincode"  pattern="[0-9]{6}" oninvalid="this.setCustomValidity(\'Please enter a valid 6-digit pincode\')" oninput="setCustomValidity(\'\')">';
            } else {
                $fitypesFeild .= '<input type="text" class="form-control" name="fi_type_id[' . $key . '][pincode]" value="" placeholder="Pincode" pattern="[0-9]{6}" oninvalid="this.setCustomValidity(\'Please enter a valid 6-digit pincode\')" oninput="setCustomValidity(\'\')">';
            }

            $fitypesFeild .= '</div>';
            $fitypesFeild .= '<div class="form-group col-md-6 col-sm-12 ' . $fitype['name'] . '_section' . ' d-none">';
            $fitypesFeild .= '<label for="phone number' . $fitype['id'] . '">' . $fitype['name'] . ' Phone Number</label>';

            if (isset($fi_type_value[$fitype['id']])) {
                $phone = $fi_type_value[$fitype['id']]['mobile'];
                $fitypesFeild .= '<input type="text" class="form-control" name="fi_type_id[' . $key . '][phone_number]" value="' . $phone . '" placeholder="Phone Number" pattern="[0-9]{10}" oninvalid="this.setCustomValidity(\'Please enter a valid 10-digit Phone Number\')" oninput="setCustomValidity(\'\')">';
            } else {
                $fitypesFeild .= '<input type="text" class="form-control" name="fi_type_id[' . $key . '][phone_number]" value="" placeholder="Phone Number" pattern="[0-9]{10}" oninvalid="this.setCustomValidity(\'Please enter a valid 10-digit Phone Number\')" oninput="setCustomValidity(\'\')">';
            }

            $fitypesFeild .= '</div>';
            $fitypesFeild .= '<div class="form-group col-md-6 col-sm-12 ' . $fitype['name'] . '_section' . ' d-none">';
            $fitypesFeild .= '<label for="landmark' . $fitype['id'] . '">' . $fitype['name'] . ' Land Mark</label>';

            if (isset($fi_type_value[$fitype['id']])) {
                $landmark = $fi_type_value[$fitype['id']]['land_mark'];
                $fitypesFeild .= '<input type="text" class="form-control" name="fi_type_id[' . $key . '][landmark]" value="' . $landmark . '" placeholder="landmark">';
            } else {
                $fitypesFeild .= '<input type="text" class="form-control" name="fi_type_id[' . $key . '][landmark]" value="" placeholder="landmark">';
            }

            $fitypesFeild .= '</div>';
        }

        return view('backend.pages.cases.reinitatiate', compact('cases', 'banks', 'roles', 'fitypes', 'fitypesFeild', 'ApplicationTypes', 'fi_type_ids', 'AvailbleProduct'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function reinitatiate(Request $request)
    {
        // Validation Data
        $request->validate([
            'applicant_name' => 'required|max:50|',
        ]);
        // Create New cases
        $cases = new Cases();
        $cases->bank_id             = $request->bank_id;
        $cases->product_id          = $request->product_id;
        $cases->application_type    = $request->application_type;
        if ($request->application_type == '1') {
            $cases->applicant_name      = $request->applicant_name;
        } elseif ($request->application_type == '2') {
            $cases->applicant_name      = $request->applicant_name;
            $cases->co_applicant_name   = $request->co_applicant_name;
        } elseif ($request->application_type == '3') {
            $cases->applicant_name      = $request->guarantee_name;
        } elseif ($request->application_type == '4') {
            $cases->applicant_name      = $request->applicant_name;
        }
        $cases->refrence_number     = $request->refrence_number;
        $cases->amount              = $request->amount ?? 0;
        $cases->geo_limit           = $request->geo_limit;
        $cases->remarks             = $request->remarks;
        $cases->created_by          = Auth::guard('admin')->user()->id;
        $cases->updated_by          = Auth::guard('admin')->user()->id;
        // $cases->name         = $request->name;
        $cases->save();
        $cases_id = $cases->id;

        foreach ($request->fi_type_id as $fi_type_id) {

            if (!empty($fi_type_id['id'])) {
                $casesFiType = new casesFiType;
                $casesFiType->case_id       = $cases_id;
                $casesFiType->fi_type_id    = $fi_type_id['id'];
                $casesFiType->mobile        = $fi_type_id['phone_number'];
                $casesFiType->address       = $fi_type_id['address'];
                $casesFiType->pincode       = $fi_type_id['pincode'];
                $casesFiType->land_mark     = $fi_type_id['landmark'];
                $casesFiType->user_id       = '0';
                $casesFiType->save();
            }
        }

        session()->flash('success', 'Case has been created !!');

        LogHelper::logActivity('Reinitatiate Case', 'User reinitatiate case.');

        CaseHistoryHelper::logHistory($cases_id, null, null, null, 'Case', 'Reinitatiate Case', 'Reinitatiate Case');

        return redirect()->route('admin.case.index');
    }

    public function reinitatiateCaseNew($id)
    {

        $casesFiType = casesFiType::with('getCase')->findOrFail($id);
        $applicentHtml = '';
        if ($casesFiType->getCase['application_type'] == '1') {
            $applicentHtml = '<div class="form-group">
                            <label for="applicant_name">Applicant Name:</label>
                            <input type="text" class="form-control" id="applicant_name" name="applicant_name" value="' . $casesFiType->getCase['applicant_name'] . '">
                        </div> ';
        } elseif ($casesFiType->getCase['application_type'] == '2') {
            $applicentHtml = '<div class="form-group">
                            <label for="applicant_name">Applicant Name:</label>
                            <input type="text" class="form-control" id="applicant_name" name="applicant_name" value="' . $casesFiType->getCase['applicant_name'] . '">
                        </div>
                        <div class="form-group">
                            <label for="applicant_name">Co-Applicant Name:</label>
                            <input type="text" class="form-control" id="applicant_name" name="applicant_name" value="' . $casesFiType->getCase['applicant_name'] . '">
                        </div> ';
        } elseif ($casesFiType->getCase['application_type'] == '3') {
            $applicentHtml = '<div class="form-group">
                            <label for="applicant_name">Guranter Name:</label>
                            <input type="text" class="form-control" id="applicant_name" name="applicant_name" value="' . $casesFiType->getCase['applicant_name'] . '">
                        </div> ';
        } elseif ($casesFiType->getCase['application_type'] == '4') {
            $applicentHtml = '<div class="form-group">
                            <label for="applicant_name">Seller Name:</label>
                            <input type="text" class="form-control" id="applicant_name" name="applicant_name" value="' . $casesFiType->getCase['applicant_name'] . '">
                        </div> ';
        }

        $htmlFormReinitatiateCase = '<div class="modal-body">
                                        <div class="form-group">
                                            <label for="refrence_number">Refrence number:</label>
                                            <input type="text" class="form-control" id="refrence_number" name="refrence_number" value="' . $casesFiType->getCase->refrence_number . '">
                                        </div>
                                        ' . $applicentHtml . '
                                        <div class="form-group">
                                            <label for="address">Address:</label>
                                            <input type="text" class="form-control" id="address" name="address" value="' . $casesFiType->address . '">
                                        </div>
                                        <div class="form-group">
                                            <label for="mobile">Phone Number:</label>
                                            <input type="text" class="form-control" id="mobile" name="mobile" value="' . $casesFiType->mobile . '" pattern="[0-9]{10}" oninvalid="this.setCustomValidity(\'Please enter a valid 10-digit Phone Number\')" oninput="setCustomValidity(\'\')">
                                        </div>
                                        <div class="form-group">
                                            <label for="land_mark">Landark:</label>
                                            <input type="text" class="form-control" id="land_mark" name="land_mark" value="' . $casesFiType->land_mark . '">
                                        </div>
                                        <div class="form-group">
                                            <label for="pincode">PinCode:</label>
                                            <input type="text" class="form-control" id="pincode" name="pincode" value="' . $casesFiType->pincode . '" pattern="[0-9]{6}" oninvalid="this.setCustomValidity(\'Please enter a valid 6-digit pincode\')" oninput="setCustomValidity(\'\')">
                                        </div>
                                        <div class="form-group">
                                            <label for="amount">Amount:</label>
                                            <input type="text" class="form-control" id="amount" name="amount" value="' . $casesFiType->getCase['amount'] . '">
                                        </div>

                                        <input type="hidden" id="case_fi_type_id" name="case_fi_type_id" value="' . $id . '">
                                    </div>
                                    ';

        if ($casesFiType !== null) {

            return response()->json(['htmlFormReinitatiateCase' => $htmlFormReinitatiateCase]);
        } else {
            return response()->json(['error' => 'Something went wrong.'], 400);
        }
    }
    public function reinitatiateNew(Request $request)
    {
        $case_fi_type_id = $request['case_fi_type_id'];
        $originalCaseFiType = casesFiType::findOrFail($case_fi_type_id);
        $case_id = $originalCaseFiType->case_id;
        $originalCaseData = Cases::findOrFail($case_id);
    
        // Replicate the original case data
        $newCasedata = $originalCaseData->replicate();
        $newCasedata->refrence_number = $request['refrence_number'];
        $newCasedata->applicant_name = $request['applicant_name'];
        $newCasedata->amount = $request->get('amount', 0);
    
        // Save the new case data
        $newCasedata->save();
    
        // Replicate the original case FI type
        // $newCaseFiType = $originalCaseFiType->replicate();
        $fitype = $originalCaseFiType->fi_type_id;
        // Set all attributes to null first
        // foreach ($newCaseFiType->getAttributes() as $key => $value) {
        //     if($newCaseFiType->$key != 'status'){
        //         $newCaseFiType->$key = null;
        //     }
        // }
    
        // Only declared fields will be set
        $casesFiType = new casesFiType;
        $casesFiType->fill([
            'fi_type_id' => $fitype,
            'applicant_name' => $request['applicant_name'],
            'address' => $request['address'],
            'mobile' => $request['mobile'],
            'land_mark' => $request['land_mark'],
            'pincode' => $request['pincode'],
            'user_id' => '0',
            'status' => '0',
            'case_id' => $newCasedata->id,
            "image_1" => null,
            "image_2" => null,
            "image_3" => null,
            "image_4" => null,
            "image_5" => null,
            "image_6" => null,
            "image_7" => null,
            "image_8" => null,
            "image_9" => null,
            "remarks" => null,
            "itr_form_remarks" => null,
            "supervisor_remarks" => null,
            "consolidated_remarks" => null,
            "app_remarks" => null,
            "status" => null,
            "sub_status" => null,
            "feedback_status" => null,
            "visit_conducted" => null,
            "scheduled_visit_date" => null,
            "address_confirmed" => null,
            "address_confirmed_by" => null,
            "person_met" => null,
            "relationship" => null,
            "no_of_residents_in_house" => null,
            "year_of_establishment" => null,
            "no_of_earning_family_members" => null,
            "residence_number" => null,
            "residence_status" => null,
            "name_of_employer" => null,
            "employer_address" => null,
            "telephone_no_residence" => null,
            "office" => null,
            "approx_value" => null,
            "approx_rent" => null,
            "designation" => null,
            "designation_other" => null,
            "pan_card" => null,
            "aadhar_card" => null,
            "assement_date" => null,
            "bank_name" => null,
            "pan_number" => null,
            "assessment_year" => null,
            "accountnumber" => null,
            "branch" => null,
            "permanent_address" => null,
            "vehicles" => null,
            "make_and_type" => null,
            "location" => null,
            "locality" => null,
            "accommodation_type" => null,
            "interior_conditions" => null,
            "assets_seen" => null,
            "area" => null,
            "standard_of_living" => null,
            "nearest_landmark" => null,
            "house_locked" => null,
            "locked_person_met" => null,
            "locked_relationship" => null,
            "applicant_age" => null,
            "occupation" => null,
            "untraceable" => null,
            "reason_of_untraceable" => null,
            "reason_of_calling" => null,
            "reason" => null,
            "verification_conducted_at" => null,
            "proof_attached" => null,
            "type_of_proof" => null,
            "audit_check_remarks_by_agency_with_stamp" => null,
            "signature_of_agency_supervisor" => null,
            "employment_details" => null,
            "comments" => null,
            "recommended" => null,
            "date_of_visit" => null,
            "time_of_visit" => null,
            "latitude" => null,
            "longitude" => null,
            "latlong_address" => null,
            "relationship_others" => null,
            "years_at_current_residence" => null,
            "years_at_current_residence_others" => null,
            "no_of_earning_family_members_others" => null,
            "residence_status_others" => null,
            "tcp1_name" => null,
            "tcp1_checked_with" => null,
            "tcp1_negative_comments" => null,
            "tcp2_name" => null,
            "tcp2_checked_with" => null,
            "tcp2_negative_comments" => null,
            "verification_conducted_at_others" => null,
            "website_of_employer" => null,
            "email_of_employer" => null,
            "co_board_outside_bldg_office" => null,
            "line_of_business" => null,
            "level_of_business_activity" => null,
            "type_of_locality" => null,
            "ease_of_locating" => null,
            "terms_of_employment" => null,
            "grade" => null,
            "name_of_employer_co" => null,
            "established" => null,
            "telephono_no_office" => null,
            "ext" => null,
            "type_of_employer" => null,
            "nature_of_employer" => null,
            "no_of_employees" => null,
            "employer_mobile" => null,
            "no_of_branches" => null,
            "not_recommended" => null,
            "nature_of_business" => null,
            "type_of_employer_co" => null,
            "type_of_profession" => null,
            "visited_by" => null,
            "verified_by" => null,
            "to_whom_does_address_belong" => null,
            "is_applicant_know_to_person" => null,
            "other_stability_year_details" => null,
            "negative_feedback_reason" => null,
            "landline" => null,
            "salary" => null,
            "total_income" => null,
            "other_income" => null,
            "dd_ref_no" => null,
            "form16_issued" => null,
            "tax_matched" => null,
            "total_income_verification" => null,
            "income_amount" => null,
            "co_applicant" => null,
            "guarantor" => null,
            "resolved_from" => null,
        ]);
        // Save the new case FI type
        $casesFiType->save();
    
        session()->flash('success', 'Case Reinitiate Successfully.');
        LogHelper::logActivity('Reinitiate Case', 'User reinitiate case as New Case.');
        CaseHistoryHelper::logHistory($newCasedata->id, 0, null, null, 'Existing Case ' . $case_id, 'Reinitiate Case', 'Reinitiate Case');
    
        return back();
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function viewCaseByCftId($id)
    {

        $cases              = casesFiType::where('id', $id)->with('getCase')->firstOrFail();
        $roles              = Role::all();
        $banks              = Bank::all();
        $fitypes            = FiType::all();
        $ApplicationTypes   = ApplicationType::all();
        $users              = User::where('admin_id', Auth::guard('admin')->user()->id)->get();

        $fi_type_ids = $fi_type_value = [];
        if ($cases->getCase()) {
            // $fi_type_ids = $cases->getCase()->pluck('fi_type_id')->toArray();
            // foreach ($cases->getCaseFiType as $value) {
            //     $fi_type_value[$value->fi_type_id] = $value;
            // }
        }

        $AvailbleProduct = [];
        if ($cases->bank_id) {
            $AvailbleProduct = Product::select('bpm.id', 'bpm.bank_id', 'bpm.product_id', 'products.name', 'products.product_code')
                ->leftJoin('bank_product_mappings as bpm', 'bpm.product_id', '=', 'products.id')
                ->where('bpm.bank_id', $cases->bank_id)
                ->where('products.status', '1')
                ->get()
                ->toArray();
        }

        $fitypesFeild = '';
        $AgentsFeild = '';
        foreach ($fitypes as $key => $fitype) {
            $fitypesFeild .= '<div class="form-group col-md-6 col-sm-12 ' . $fitype['name'] . '_section' . ' d-none">';
            $fitypesFeild .= '<label for="Address' . $fitype['id'] . '">' . $fitype['name'] . ' Address</label>';

            if (isset($fi_type_value[$fitype['id']])) {
                $address = $fi_type_value[$fitype['id']]['address'];
                $fitypesFeild .= '<input type="text" class="form-control" name="fi_type_id[' . $key . '][address]" value="' . $address . '" placeholder="Address">';
            } else {
                $fitypesFeild .= '<input type="text" class="form-control" name="fi_type_id[' . $key . '][address]" value="" placeholder="Address">';
            }

            $fitypesFeild .= '</div>';
            $fitypesFeild .= '<div class="form-group col-md-6 col-sm-12 ' . $fitype['name'] . '_section' . ' d-none">';
            $fitypesFeild .= '<label for="Pincode' . $fitype['id'] . '">' . $fitype['name'] . ' Pincode</label>';

            if (isset($fi_type_value[$fitype['id']])) {
                $pincode = $fi_type_value[$fitype['id']]['pincode'];
                $fitypesFeild .= '<input type="text" class="form-control" name="fi_type_id[' . $key . '][pincode]" value="' . $pincode . '" placeholder="Pincode" pattern="[0-9]{6}" oninvalid="this.setCustomValidity(\'Please enter a valid 6-digit pincode\')" oninput="setCustomValidity(\'\')">';
            } else {
                $fitypesFeild .= '<input type="text" class="form-control" name="fi_type_id[' . $key . '][pincode]" value="" placeholder="Pincode" pattern="[0-9]{6}" oninvalid="this.setCustomValidity(\'Please enter a valid 6-digit pincode\')" oninput="setCustomValidity(\'\')">';
            }

            $fitypesFeild .= '</div>';
            $fitypesFeild .= '<div class="form-group col-md-6 col-sm-12 ' . $fitype['name'] . '_section' . ' d-none">';
            $fitypesFeild .= '<label for="phone number' . $fitype['id'] . '">' . $fitype['name'] . ' Phone Number</label>';

            if (isset($fi_type_value[$fitype['id']])) {
                $phone = $fi_type_value[$fitype['id']]['mobile'];
                $fitypesFeild .= '<input type="text" class="form-control" name="fi_type_id[' . $key . '][phone_number]" value="' . $phone . '" placeholder="Phone Number" pattern="[0-9]{10}" oninvalid="this.setCustomValidity(\'Please enter a valid 10-digit Phone Number\')" oninput="setCustomValidity(\'\')">';
            } else {
                $fitypesFeild .= '<input type="text" class="form-control" name="fi_type_id[' . $key . '][phone_number]" value="" placeholder="Phone Number" pattern="[0-9]{10}" oninvalid="this.setCustomValidity(\'Please enter a valid 10-digit Phone Number\')" oninput="setCustomValidity(\'\')">';
            }

            $fitypesFeild .= '</div>';
            $fitypesFeild .= '<div class="form-group col-md-6 col-sm-12 ' . $fitype['name'] . '_section' . ' d-none">';
            $fitypesFeild .= '<label for="landmark' . $fitype['id'] . '">' . $fitype['name'] . ' Land Mark</label>';

            if (isset($fi_type_value[$fitype['id']])) {
                $landmark = $fi_type_value[$fitype['id']]['land_mark'];
                $fitypesFeild .= '<input type="text" class="form-control" name="fi_type_id[' . $key . '][landmark]" value="' . $landmark . '" placeholder="landmark">';
            } else {
                $fitypesFeild .= '<input type="text" class="form-control" name="fi_type_id[' . $key . '][landmark]" value="" placeholder="landmark">';
            }

            $fitypesFeild .= '</div>';
        }

        LogHelper::logActivity('View Case', 'User view case.');

        return view('backend.pages.cases.show', compact('cases', 'banks', 'roles', 'fitypes', 'fitypesFeild', 'ApplicationTypes', 'fi_type_ids', 'AvailbleProduct'));
    }

    public function getCase($case_fi_type_id = null)
    {
        $case_fi_type = casesFiType::findOrFail($case_fi_type_id);

        if ($case_fi_type !== null) {
            LogHelper::logActivity('Get Case', 'User fetch case record.');
            return response()->json(['case_fi_type' => $case_fi_type]);
        } else {
            return response()->json(['error' => 'Bank ID not provided.'], 400);
        }
    }
    public function editCase($id)
    {
        $case = casesFiType::with(['getUser', 'getCase', 'getCaseFiType', 'getFiType', 'getCaseStatus'])->where('id', $id)->firstOrFail();
        $assign = false;
        $is_edit_case  = '1';
        $ApplicationTypes   = ApplicationType::all();
        $users              = User::where('admin_id', Auth::guard('admin')->user()->id)->get();
        return view('backend.pages.cases.editcase', compact('case', 'assign', 'is_edit_case', 'ApplicationTypes', 'users'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateCase(Request $request, $id)
    {
        $cases = Cases::findOrFail($id);
        $cases->bank_id             = $request->bank_id ??  $cases->bank_id;
        $cases->product_id          = $request->product_id ??   $cases->product_id;
        $cases->application_type    = $request->application_type ??  $cases->application_type;
        if ($request->application_type == '1') {
            $cases->applicant_name      = $request->applicant_name ?? $cases->applicant_name;
        } elseif ($request->application_type == '2') {
            $cases->applicant_name      = $request->applicant_name ?? $cases->applicant_name;
            $cases->co_applicant_name   = $request->co_applicant_name ?? $cases->co_applicant_name;
        } elseif ($request->application_type == '3') {
            $cases->applicant_name      = $request->guarantee_name ?? $cases->applicant_name;
        } elseif ($request->application_type == '4') {
            $cases->applicant_name      = $request->applicant_name ??  $cases->applicant_name;
        }
        $cases->refrence_number     = $request->refrence_number ??  $cases->refrence_number;
        $cases->amount              = $request->amount ??   $cases->amount;
        $cases->geo_limit           = $request->geo_limit ??   $cases->geo_limit;
        $cases->remarks             = $request->remarks ??   $cases->remarks;
        $cases->updated_by          = Auth::guard('admin')->user()->id;
        $cases->save();
        foreach ($request->fi_type_id as $fi_type_id) {
            if (!empty($fi_type_id['id'])) {
                casesFiType::updateOrInsert(
                    ['case_id' => $cases->id, 'fi_type_id' => $fi_type_id['id']],
                    ['mobile' => $fi_type_id['phone_number'], 'address' => $fi_type_id['address'], 'pincode' => $fi_type_id['pincode'], 'land_mark' => $fi_type_id['land_mark'], 'user_id' => 0]
                );
            }
        }

        session()->flash('success', 'Case has been updated !!');
        LogHelper::logActivity('Update Case', 'User update case.');
        CaseHistoryHelper::logHistory($id, null, null, null, 'Existing Case', 'Update Case', 'Update Case');
        return redirect()->route('admin.case.index');
    }
    public function uploadCaseImage($case_fi_type_id)
    {
        $case_img = CasesFiType::findOrFail($case_fi_type_id);
        return view('backend.pages.cases.uploadImage', compact('case_img', 'case_fi_type_id'));
    }

    public function originalCaseImage($case_fi_type_id)
    {
        $case_img = DB::table('original_images')->where('case_fi_id', $case_fi_type_id)->get();
        return view('backend.pages.cases.originalCaseImage', compact('case_img', 'case_fi_type_id'));
    }


    public function wrapText($img, $text, $maxWidth, $fontSize, $fontPath)
    {
        $wrappedText = '';
        $words = explode(' ', $text);
        $currentLine = '';

        foreach ($words as $word) {
            $testLine = $currentLine . $word . ' ';
            $box = imagettfbbox($fontSize, 0, $fontPath, $testLine);
            $lineWidth = $box[2] - $box[0];

            if ($lineWidth > $maxWidth) {
                $wrappedText .= $currentLine . "\n";
                $currentLine = $word . ' ';
            } else {
                $currentLine = $testLine;
            }
        }
        $wrappedText .= $currentLine;

        return $wrappedText;
    }
    public function uploadImage(Request $request, $case_fi_type_id)
    {

        // Validate the uploaded files
        $request->validate([
            'images.*' => 'required|image|mimes:jpeg,jpg,png,gif,svg|max:2048', // max size 2MB per image
        ]);

        // Find the record by ID
        $case = CasesFiType::findOrFail($case_fi_type_id);

        $latitude = $case->latitude;
        $longitude = $case->longitude;
        $latlong_address = wordwrap($case->latlong_address, 50, "\n");
        $dateTime = isset($case->date_of_visit) && !empty($case->date_of_visit)
            ? date('d/m/Y', strtotime($case->date_of_visit)) . ' ' . date('H:i', strtotime($case->time_of_visit))
            : date('d-m-Y H:i:s');

        $year = date('Y');
        $month = date('m');
        $path = "images/cases/{$year}/{$month}";

        if (!file_exists(public_path($path))) {
            mkdir(public_path($path), 0777, true);
        }

        foreach ($request->file('images') as $file) {
            $imgField = $this->getAvailableImageField($case);

            if ($imgField) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $filePath = "{$path}/{$filename}";

                $file->move(public_path($path), $filename);

                $img = Image::make(public_path($filePath));
                $img->resize(1500, 2000);

                // Table properties
                $tableStartX = 50;
                $tableStartY = $img->height() - 500;
                $tableWidth = $img->width() - $tableStartX; // Total width of the table
                $rowHeight = 100;


                // Draw table and add text
                $this->addTextToImage($img, $tableStartX, $tableStartY, $rowHeight, $tableWidth, $latitude, $longitude, $latlong_address, $dateTime);

                $img->save(public_path($filePath));

                $case->$imgField = $filePath;
                $case->save();

                session()->flash('success', 'Image uploaded successfully');
            } else {
                session()->flash('error', 'All image slots are filled');
                break;
            }
        }

        // Log activity
        LogHelper::logActivity('Upload Document', 'User uploaded a document with latitude and longitude.');
        return back();
    }

    private function drawLineWithWidth($img, $x1, $y1, $x2, $y2, $color, $thickness)
    {
        $angle = atan2($y2 - $y1, $x2 - $x1); // Calculate the angle of the line
        for ($i = - ($thickness / 2); $i < ($thickness / 2); $i++) {
            $offsetX = $i * sin($angle);
            $offsetY = $i * cos($angle);
            $img->line(
                $x1 + $offsetX,
                $y1 - $offsetY,
                $x2 + $offsetX,
                $y2 - $offsetY,
                function ($draw) use ($color) {
                    $draw->color($color);
                }
            );
        }
    }

    private function drawTableBorders($img, $tableStartX, $tableStartY, $rowHeight, $rowCount, $col1Width, $col2Width)
    {
        $tableWidth = $col1Width + $col2Width;
        $tableHeight = $rowHeight * $rowCount;


        // Draw outer borders
        $this->drawLineWithWidth($img, $tableStartX, $tableStartY, $tableStartX + $tableWidth, $tableStartY, '#FFFFFF', 3); // Top
        $this->drawLineWithWidth($img, $tableStartX, $tableStartY, $tableStartX, $tableStartY + $tableHeight, '#FFFFFF', 3); // Left
        $this->drawLineWithWidth($img, $tableStartX + $tableWidth, $tableStartY, $tableStartX + $tableWidth, $tableStartY + $tableHeight, '#FFFFFF', 3); // Right
        $this->drawLineWithWidth($img, $tableStartX, $tableStartY + $tableHeight, $tableStartX + $tableWidth, $tableStartY + $tableHeight, '#FFFFFF', 3); // Bottom

        // Draw horizontal lines
        for ($i = 1; $i < $rowCount; $i++) {
            $y = $tableStartY + $rowHeight * $i;
            $this->drawLineWithWidth($img, $tableStartX, $y, $tableStartX + $tableWidth, $y, '#FFFFFF', 3);
        }

        // Draw vertical line between columns
        $this->drawLineWithWidth($img, $tableStartX + $col1Width, $tableStartY, $tableStartX + $col1Width, $tableStartY + $tableHeight, '#FFFFFF', 3);
    }

    private function addTextToImage($img, $tableStartX, $tableStartY, $rowHeight, $tableWidth, $latitude, $longitude, $latlong_address, $dateTime)
    {
        $col1Width = $img->width() * 0.25; // 25% for the first column
        $col2Width = $img->width() * 0.72; // 72% for the second column
        $padding = $img->width() * 0.03; // 3% for the second column
        $tableWidth = $col1Width + $col2Width - $padding;
        $tableHeight = $rowHeight * 4;

        // Draw background rectangle for the table
        $img->rectangle(
            $tableStartX,
            $tableStartY,
            $tableStartX + $tableWidth,
            $tableStartY + $tableHeight,
            function ($draw) {
                $draw->background([0, 0, 0, 0.5]); // Semi-transparent black
            }
        );
        // Add Latitude
        $img->text('Address', $tableStartX + 10, $tableStartY + $rowHeight / 2, function ($font) {
            $font->file(public_path('fonts/ARIAL.TTF'));
            $font->size(40);
            $font->color('#fff'); // White text color
            $font->align('left');
            $font->valign('middle');
        });

        $img->text($latlong_address, $tableStartX + $col1Width + 10, $tableStartY + $rowHeight / 2, function ($font) {
            $font->file(public_path('fonts/ARIAL.TTF'));
            $font->size(40);
            $font->color('#fff'); // White text color
            $font->align('left');
            $font->valign('middle');
        });
        // Add Latitude
        $img->text("Latitude", $tableStartX + 10, $tableStartY + $rowHeight + $rowHeight / 2, function ($font) {
            $font->file(public_path('fonts/ARIAL.TTF'));
            $font->size(40);
            $font->color('#FFFFFF'); // White text color
            $font->align('left');
            $font->valign('middle');
        });
        $img->text($latitude, $tableStartX + $col1Width + 10, $tableStartY + $rowHeight + $rowHeight / 2, function ($font) {
            $font->file(public_path('fonts/ARIAL.TTF'));
            $font->size(40);
            $font->color('#fff'); // White text color
            $font->align('left');
            $font->valign('middle');
        });

        // Add Longitude
        $img->text("Longitude", $tableStartX + 10, $tableStartY + $rowHeight + $rowHeight + $rowHeight / 2, function ($font) {
            $font->file(public_path('fonts/ARIAL.TTF'));
            $font->size(40);
            $font->color('#fff'); // White text color
            $font->align('left');
            $font->valign('middle');
        });
        $img->text($longitude, $tableStartX + $col1Width + 10, $tableStartY + $rowHeight + $rowHeight + $rowHeight / 2, function ($font) {
            $font->file(public_path('fonts/ARIAL.TTF'));
            $font->size(40);
            $font->color('#fff'); // White text color
            $font->align('left');
            $font->valign('middle');
        });

        $img->text('Date', $tableStartX + 10, $tableStartY + $rowHeight + $rowHeight + $rowHeight  + $rowHeight / 2, function ($font) {
            $font->file(public_path('fonts/ARIAL.TTF'));
            $font->size(40);
            $font->color('#fff'); // White text color
            $font->align('left'); // Center align text
            $font->valign('middle');
        });
        $img->text($dateTime, $tableStartX + $col1Width + 10, $tableStartY + $rowHeight + $rowHeight + $rowHeight  + $rowHeight / 2, function ($font) {
            $font->file(public_path('fonts/ARIAL.TTF'));
            $font->size(40);
            $font->color('#fff'); // White text color
            $font->align('left'); // Center align text
            $font->valign('middle');
        });
        // Draw table borders
        $this->drawTableBorders($img, $tableStartX, $tableStartY, $rowHeight, 4, $col1Width, $col2Width);
    }

    private function getAvailableImageField($case)
    {
        for ($i = 1; $i <= 9; $i++) {
            $imgField = 'image_' . $i;
            if (is_null($case->$imgField)) {
                return $imgField;
            }
        }
        return null;
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cases = Cases::find($id);
        if (!is_null($cases)) {
            $cases->delete();
        }

        session()->flash('success', 'Cases has been deleted !!');
        LogHelper::logActivity('Delete Case', 'User delete case.');
        return back();
    }

    public function getItem($bankId = null)
    {
        $AvailbleProduct = Product::select('bpm.id', 'bpm.bank_id', 'bpm.product_id', 'products.name', 'products.product_code')
            ->leftJoin('bank_product_mappings as bpm', 'bpm.product_id', '=', 'products.id')
            ->where('bpm.bank_id', $bankId)
            ->where('products.status', '1')
            ->get()->toArray();


        if ($bankId !== null) {
            return response()->json(['AvailbleProduct' => $AvailbleProduct]);
        } else {
            return response()->json(['error' => 'Bank ID not provided.'], 400);
        }
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function importExportView($bankId = 1)
    {
        if (Auth::guard('admin')->user()->id != 1) {
            $assignBank = explode(',', Auth::guard('admin')->user()->banks_assign);
            $banks = Bank::whereIn('id', $assignBank)->get();
        } else {
            $banks = Bank::all();
        }
        return view('backend.pages.cases.import', compact('banks'));
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function import(Request $request)
    {
        // Validate that the file is uploaded
        $request->validate([
            'file' => 'required|mimes:xlsx,csv|max:2048', // Validate file type and size
        ]);

        // Store the uploaded file temporarily in the 'uploads' directory
        $file = $request->file('file');
        $filePath = $file->storeAs('uploads', $file->getClientOriginalName()); // Save with original name

        // Read the file into an array
        $rows = Excel::toArray([], storage_path('app/' . $filePath));
        $failedRows = []; // Array to store failed rows and their errors

        // Iterate over the rows and process each row
        foreach ($rows[0] as $key => $row) {
            // Convert specific columns to integer type
            $row[13] = intval($row[13]);
            $row[11] = intval($row[11]);
            $row[12] = intval($row[12]);

            $data = [
                'mobile'  => $row[13],
                'pincode' => $row[11],
                'landline' => $row[12],
                'product_id' => $row[15],
                'branch_code' => $row[16],
                'verification_type' => $row[16],
            ];

            if ($key > 0 && !empty($row[0])) {  // Skip the header row
                // Validate the row data
                $validator = Validator::make($data, [
                    'product_id'  => 'required',
                    'branch_code'  => 'required',
                    'verification_type'  => 'required',
                ]);

                if ($validator->fails()) {
                    // Collect the failed row and errors
                    $failedRows[] = [
                        'row' => $row,
                        'errors' => $validator->errors()->toArray(),
                    ];
                    continue;
                } else {
                    // Look up branch and product by code
                    $branch_code = $row[16];
                    $branch = BranchCode::where('branch_code', $branch_code)->first();
                    $product_code = $row[15];
                    $product = Product::where('product_code', $product_code)->first();
                    $fitype_data    = $row[14];
                    $user_id        = '0';
                    $status         = '0';
                    $agent_details = User::where('username', '=', $fitype_data)->first();
                    if ($agent_details) {
                        $user_id = $agent_details['id'];
                        $status = '1';
                    }
                    if (!$agent_details) {
                        // Collect the failed row and error message
                        $failedRows[] = [
                            'row' => $row,
                            'errors' => ["Agent not found for given name: {$agent_details}"],
                        ];
                        continue;
                    }
                    if (!$branch || !$product) {
                        // Collect the failed row and error message
                        $failedRows[] = [
                            'row' => $row,
                            'errors' => ["Branch or Product not found for given codes: Branch Code {$branch_code}, Product Code {$product_code}"],
                        ];
                        continue;
                    }

                    // Convert Excel date to standard date format
                    $tatstart = $this->convertExcelDate($row[17]);
                    $tatend = $this->convertExcelDate($row[18]);
                    // $assementDate = $this->convertExcelDate($row[22]);

                    $existing_cases_count = Cases::where('branch_code', $branch->id)->count();
                    $automatic_number = str_pad($existing_cases_count + 1, 5, '0', STR_PAD_LEFT); // 5-digit padded number
                    $reference_number = "SRM/{$branch_code}/{$automatic_number}";

                    try {
                        // Save the case data
                        $cases = new Cases();
                        $cases->bank_id             = $request->bank_id;
                        $cases->product_id          = $product->id;
                        $cases->branch_code         = $branch->id;
                        $cases->application_type    = '3';
                        $cases->refrence_number     = $reference_number;
                        $cases->applicant_name      = $row[5];
                        $cases->pan_card            = $row[19] ?? null;
                        $cases->aadhar_card         = $row[20] ?? null;
                        $cases->account_number      = $row[22];
                        $cases->tat_start           = $tatstart;
                        $cases->tat_end             = $tatend;
                        $cases->amount              = '0';
                        $cases->created_by          = Auth::guard('admin')->user()->id;
                        $cases->updated_by          = 0;
                        $cases->save();
                        $cases_id = $cases->id;

                        // Additional data processing for FiType and casesFiType...
                        $fitype_data = $row[6];
                        $fitype_details = FiType::where('name', '=', $fitype_data)->first();

                        if ($fitype_details) {
                            $fitype_id = $fitype_details['id'];
                        } else {
                            $fitype = new FiType();
                            $fitype->name         = $fitype_data;
                            $fitype->created_by   = Auth::guard('admin')->user()->id;
                            $fitype->updated_by   = 0;
                            $fitype->save();
                            $fitype_id = $fitype->id;
                        }

                        // Save casesFiType information
                        $casesFiType = new casesFiType();
                        $casesFiType->case_id       = $cases_id;
                        $casesFiType->fi_type_id    = $fitype_id;
                        $casesFiType->mobile        = $row['13'];
                        $casesFiType->address       = $row['8'];
                        $casesFiType->pincode       = $row['11'];
                        $casesFiType->dealer_code   = $row['3'];
                        $casesFiType->landline      = $row['12'];
                        $casesFiType->company_name  = $row['7'];
                        $casesFiType->pan_number          = $row[19] ?? null;
                        $casesFiType->aadhar_card         = $row[20] ?? null;
                        $casesFiType->assessment_year     = $row[21] ?? null;
                        $casesFiType->accountnumber      = $row[22];
                        $casesFiType->tat_start     = $tatstart;
                        $casesFiType->tat_end       = $tatend;
                        $casesFiType->branch_code   = $branch->id;
                        $casesFiType->status        = $status;
                        $casesFiType->user_id       = $user_id;
                        $casesFiType->save();
                    } catch (\Exception $e) {
                        // Collect the failed row and error message if saving fails
                        $failedRows[] = [
                            'row' => $row,
                            'errors' => [$e->getMessage()],
                        ];
                        continue;
                    }
                }
            }
        }

        // Delete the file after processing
        // Delete the file after processing
        Storage::delete($filePath);
        $failedRows = isset($failedRows) ? $failedRows : [];
        $success = 'Uploaded Successfully!';

        // Return success message and redirect to index
        return redirect()->route('admin.case.index')->with(compact('success', 'failedRows'));
    }

    private function convertExcelDate($value)
    {
        // Check if it's a numeric value (which Excel uses for dates)
        if (is_numeric($value)) {
            return Carbon::instance(ExcelDate::excelToDateTimeObject($value));  // Convert Excel date to Carbon
        }

        // If it's not numeric (like already in a date format), return as it is
        return Carbon::parse($value);
    }


    public function caseStatus(Request $request, $status, $user_id = null)
    {
        $user_id = $user_id ?? 0;
        $assign = false;
        $user = Auth::guard('admin')->user();
        $fitype = FiType::all();
        $branchcode = BranchCode::all();
        // Default records per page
        $perPage = $request->get('perPage', 10); // Use 10 as the default value

        // Initialize the case query with necessary relationships
        $casequery = casesFiType::with([
            'getUser',
            'getCase',
            'getCase.getCreatedBy',
            'getCaseFiType',
            'getFiType',
            'getBranch',
            'getCaseStatus'
        ]);
        $is_pagination = !$request->has('is_pagination') ? $request->is_pagination : false;
        // Status filter
        if ($user->role !== 'Bank') {
            if (in_array($status, [4, 5, 7])) {
                if (!$request->ajax() && !$is_pagination) {
                    $casequery->whereDate('updated_at', date('Y-m-d'));
                    // ->orWhereDate('created_at', date('Y-m-d'));
                }
            }
        }

        // Role-based filtering
        if ($user->role === 'superadmin') {
            if ($status !== 'aaa') {
                $casequery->where('status', $status);
            }
            if ($user_id != 0) {
                $casequery->where('user_id', $user_id);
            }
        } else {
            if ($user->role === 'Bank') {
                $casequery->whereHas('getCase', function ($query) use ($user) {
                    $query->where('created_by', $user->id);
                });
            }
            if ($user_id != 0) {
                $casequery->where('user_id', $user_id);
            }
            $assignBank = explode(',', $user->banks_assign);
            $casequery->whereHas('getCase', function ($query) use ($assignBank) {
                $query->whereIn('bank_id', $assignBank);
            });
            
            if ($status !== 'aaa') {
                $casequery->where('status', $status);
            }
        }

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $casequery->where(function ($query) use ($search) {
                $query->whereHas('getCase', function ($casequery) use ($search) {
                    $casequery->where('refrence_number', 'LIKE', "%$search%")
                        ->orWhere('applicant_name', 'LIKE', "%$search%");
                })->orWhere('mobile', 'LIKE', "%$search%");
            });
        }

        // Paginate with records per page
        $cases = $casequery->paginate($perPage);

        if ($request->ajax()) {
            return view('backend.pages.cases.caseTable', compact('cases', 'assign'))->render();
        }

        return view('backend.pages.cases.caseList', compact('cases', 'assign', 'status', 'user_id', 'perPage', 'fitype', 'branchcode'));
    }


    public function assigned($status, $user_id = null)
    {

        $cases  = DB::table('cases_fi_types as cft')
            ->join('cases as c', 'c.id', '=', 'cft.case_id')
            ->join('fi_types as ft', 'ft.id', '=', 'cft.fi_type_id')
            ->leftJoin('users as u', 'u.id', '=', 'cft.user_id')
            ->where('cft.user_id', '0')
            ->select('cft.id', 'c.refrence_number', 'c.applicant_name',  'c.co_applicant_name', 'cft.mobile', 'cft.address', 'ft.name',  'cft.scheduled_visit_date', 'cft.status', 'u.name as agent_name')
            ->get();

        return view('backend.pages.cases.caseList', compact('cases'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function assignAgent(Request $request)
    {
        // Validation Data
        $request->validate([
            'user_id' => 'required|max:50|',
        ]);
        $user_id                = $request['user_id'];
        $scheduled_visit_date   = $request['ScheduledVisitDate'];
        $case_fi_type_ids       = $request['case_fi_type_id'];

        $case_fi_type_ids = json_decode($case_fi_type_ids, true);
        foreach ($case_fi_type_ids as $case_fi_type_id) {
            $case_fi_type_id = (int) $case_fi_type_id;

            $cases = casesFiType::find($case_fi_type_id);
            $cases->user_id                 = $user_id;
            $cases->scheduled_visit_date    = $scheduled_visit_date;
            $cases->status                  = '1';
            $cases->save();
            CaseHistoryHelper::logHistory($cases->case_id, 1, null, $user_id, 'New Case', 'Assign Case', 'Assign Case');
        }
        session()->flash('success', 'User assign successfully !!');
        LogHelper::logActivity('Assign Case', 'User assign case.');
        return back();
    }


    public function updatefitype(Request $request, $id)
    {
        $case_tableid = $id;
        $cases = casesFiType::find($case_tableid);

        if ($cases) {
            $cases->fi_type_id = $request->fi_type_id; // Update fi_type_id
            $cases->save();

            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false, 'message' => 'Case not found']);
        }
    }
    public function updateproduct(Request $request, $id)
    {
        $case_tableid = $id;
        $cases = Cases::find($case_tableid);

        if ($cases) {
            $cases->product_id = $request->product_id; // Update fi_type_id
            $cases->save();

            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false, 'message' => 'Case not found']);
        }
    }
    public function updatebranchcode(Request $request, $id)
    {
        $case_tableid = $id;
        $cases = Cases::find($case_tableid);

        if ($cases) {
            $cases->branch_code = $request->branch_code; // Update fi_type_id
            $cases->save();

            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false, 'message' => 'Case not found']);
        }
    }
    public function resolveCase(Request $request)
    {
        $case_fi_type_id = $request->case_fi_type_id;
        $cases = casesFiType::find($case_fi_type_id);

        if (!$cases) {
            return back()->with('error', 'Case not found.');
        }

        $agent = User::find($cases->user_id);

        $sub_status = $request->sub_status;
        $status = in_array($sub_status, [2, 111, 1138, 1139, 1140]) ? '2' : '3';
        $consolidated_remarks = $request->consolidated_remarks;

        // Update case attributes
        $cases->status = $status;
        $cases->overall_status = $status;
        $cases->sub_status = $sub_status;
        $cases->consolidated_remarks = $consolidated_remarks; 
        $cases->visited_by = $agent->name;
        $cases->date_of_visit = date('Y-m-d');
        $cases->time_of_visit = date('H:i:s');
        $cases->resolved_from = 'web';
        $cases->resolved_by = Auth::guard('admin')->user()->id;
        $cases->resolved_at = now();

        // Save financial_year[] and fi_remarks[] in itr_form_remarks if fi_type == 12
        // if ($cases->fi_type_id == 12 || $cases->fi_type_id == 13) {
        if ($cases->fi_type_id == 12) {
            $financial_years = $request->input('financial_year');
            $fi_remarks = $request->input('fi_remarks');

            if (is_array($financial_years) && is_array($fi_remarks)) {
                $itr_form_remarks = [];

                foreach ($financial_years as $index => $financial_year) {
                    // Skip if either financial_year or fi_remark is missing
                    if (empty($financial_year) || empty($fi_remarks[$index])) {
                        continue;
                    }

                    $itr_form_remarks[] = [
                        'financial_year' => $financial_year,
                        'fi_remark' => $fi_remarks[$index],
                    ];
                }

                // Encode the data as JSON and save in itr_form_remarks field
                $cases->itr_form_remarks = json_encode($itr_form_remarks);
            }
        }

        $update = $cases->save();

        session()->flash('success', 'Case resolved successfully!');
        CaseHistoryHelper::logHistory(
            $cases->case_id,
            $status,
            $sub_status,
            $cases->user_id,
            $consolidated_remarks,
            'Resolve Case',
            'Resolve Case'
        );
        LogHelper::logActivity('Resolve Case', 'User resolved the case.');

        return redirect()->back();
    }


    public function verifiedCase(Request $request)
    {
        $user = Auth::guard('admin')->user();
        if ($user->role == 'Bank') {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }
        $case_fi_type_id                = $request->case_fi_type_id;
        $status                         = $request->feedback_status == 111 ? '4' : ($request->feedback_status == 1155 ? '1155' : '5');
        $sub_status                     = $request->sub_status;
        $supervisor_remarks             = $request->remarks;
        $cases                          = casesFiType::find($case_fi_type_id);
        $agent  =   User::find($cases->user_id);
        $cases->status                  = $status;
        // $cases->overall_status       = $status;
        $cases->feedback_status         = $request->feedback_status;
        $cases->sub_status              = $sub_status;
        $cases->supervisor_remarks      = $supervisor_remarks;
        $cases->visited_by              = isset($agent) ? $agent->name : '';
        $cases->verified_by             = Auth::guard('admin')->user()->name;
        $cases->verifiers_name          = Auth::guard('admin')->user()->id;
        $cases->verified_at             = now();
        $cases->save();
        session()->flash('success', 'Case Resolve successfully !!');
        CaseHistoryHelper::logHistory($cases->case_id, $status, $sub_status, $cases->user_id, $supervisor_remarks, 'Verified Case', 'Verified Case');
        LogHelper::logActivity('Verify Case', 'User verify case.');
        return redirect()->back();
    }


    public function updateConsolidated(Request $request)
    {

        $case_fi_type_id                = $request['case_fi_type_id'];
        $consolidated_remarks           = $request['consolidated_remarks'];
        $cases                          = casesFiType::find($case_fi_type_id);
        $cases->consolidated_remarks    = $consolidated_remarks;
        $cases->save();
        session()->flash('success', 'Remark Update successfully !!');
        LogHelper::logActivity('Update Remark', 'User update remark of the case.');
        CaseHistoryHelper::logHistory($cases->case_id, $cases->status, $cases->sub_status, $cases->user_id, $consolidated_remarks, 'Update Remark Case', 'Update Remark Case');
        return redirect()->back();
    }

    public function closeCase($case_fi_type_id)
    {

        $cases  = casesFiType::find($case_fi_type_id);
        $cases->status     = '7';
        if(empty($cases->verifiers_name)) {
            $cases->verified_by = Auth::guard('admin')->user()->name;
            $cases->verifiers_name = Auth::guard('admin')->user()->id;
        }
        $cases->closed_by = Auth::guard('admin')->user()->id;
        $cases->closed_at = now();
        $cases->save();
        LogHelper::logActivity('Close Case', 'User close case status.');
        CaseHistoryHelper::logHistory($cases->case_id, 7, $cases->sub_status, $cases->user_id, $cases->consolidated_remarks, 'Close Case', 'Close Case');
        return response()->json(['success' => 'Case Close successfully.'], 200);
    }
    public function cloneCase($case_fi_type_id)
    {
        $originalCaseFiType  = casesFiType::findOrFail($case_fi_type_id);
        $case_id = $originalCaseFiType->case_id;
        $originalCaseData  = Cases::findOrFail($case_id);
        $newCasedata = $originalCaseData->replicate();
        $newCasedata->save();

        $newCaseFiType = $originalCaseFiType->replicate();
        // $newCaseFiType->status = '0';
        $newCaseFiType->case_id = $newCasedata->id;
        $newCaseFiType->save();

        // Duplicate related case_fi_types

        LogHelper::logActivity('Clone Case', 'User create clone of the case.');
        CaseHistoryHelper::logHistory($newCasedata->id, null, null, null, 'Clone Case ' . $case_id, 'Clone Case', 'Clone Case');
        return response()->json(['success' => 'Case Clone successfully.'], 200);
    }

    public function deleteCase($case_fi_type_id)
    {
        // Attempt to find the case or return a 404 response if not found
        $caseFiType = casesFiType::find($case_fi_type_id);

        if (!$caseFiType) {
            return response()->json([
                'success' => "CaseFiType with ID {$case_fi_type_id} not found."
            ], 404);
        }

        // Delete the case
        $caseFiType->delete();

        return response()->json([
            'success' => "CaseFiType with ID {$case_fi_type_id} deleted successfully."
        ], 200);
    }

    public function deleteImage(Request $request, $image_number)
    {

        $case_fi_type_id = $request['case_fi_type_id'];
        $imgNumber = 'image_' . $image_number;

        $cases = casesFiType::findOrFail($case_fi_type_id);
        $img = $cases->$imgNumber;
        $cases->$imgNumber     = NULL;
        // $cases->updated_by     = Auth::guard('admin')->user()->id;
        $cases->save();
        // Delete the file if it exists
        if ($img && file_exists(public_path($img))) {
            unlink(public_path($img));
        }
        // session()->flash('success', 'Image uploaded successfully');
        LogHelper::logActivity('Remove Document', 'User remove case supported document.');
        return response()->json(['success' => 'Image delete successfully.'], 200);
    }

    private function importCSV($file)
    {
        if (($handle = fopen($file->getRealPath(), 'r')) !== FALSE) {
            $header = fgetcsv($handle, 1000, ',');
            while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                $row = array_combine($header, $data);
                DB::table('your_table')->insert([
                    'column1' => $row['Header1'],
                    'column2' => $row['Header2'],
                    // more columns...
                ]);
            }
            fclose($handle);
        }
    }


    private function importExcel($file)
    {
        /*
        $spreadsheet = IOFactory::load($file->getRealPath());
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        $header = $sheetData[1];
        unset($sheetData[1]); // Remove header row
        foreach ($sheetData as $row) {
            $data = array_combine($header, $row);
            DB::table('your_table')->insert([
                'column1' => $data['A'], // Adjust based on your Excel columns
                'column2' => $data['B'],
                // more columns...
            ]);
        } */

        return true;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function exportCase($status = null, $user_id = null)
    {
        LogHelper::logActivity('Export Case', 'User export case.');
        // Pass parameters to the ExportCase instance
        return Excel::download(new ExportCase($status, $user_id), 'cases.xlsx');
    }

    public function viewCase($id)
    {
        $case = casesFiType::with(['getUser', 'getCase', 'getCaseFiType', 'getFiType', 'getCaseStatus'])->where('id', $id)->firstOrFail();
        $assign = false;
        $is_edit_case  = '0';
        $ApplicationTypes   = ApplicationType::all();
        $users              = User::where('admin_id', Auth::guard('admin')->user()->id)->get();
        return view('backend.pages.cases.editcase', compact('case', 'assign', 'is_edit_case', 'ApplicationTypes', 'users'));
    }
    public function viewCaseAssign($id)
    {
        $case = casesFiType::with(['getUser', 'getCase', 'getCaseFiType', 'getFiType', 'getCaseStatus'])->where('id', $id)->firstOrFail();
        $assign = true;
        return view('backend.pages.cases.view', compact('case', 'assign'));
    }

    public function updategeolimit(Request $request, $id)
    {
        $case_tableid = $id;
        $cases = Cases::find($case_tableid);

        if ($cases) {
            // Validate geo_limit for select options
            $request->validate([
                'geo_limit' => 'required|in:Local,Outstation'
            ]);
            
            $cases->geo_limit = $request->geo_limit;
            $cases->save();

            // Log the activity
            LogHelper::logActivity('Update Geo Limit', 'User updated geo limit for case ID: ' . $case_tableid);
            CaseHistoryHelper::logHistory($case_tableid, null, null, null, 'Geo Limit Updated', 'Update Geo Limit', 'Geo Limit updated to: ' . $request->geo_limit);

            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false, 'message' => 'Case not found']);
        }
    }

    public  function modifyCase(Request $request, $id)
    {

        $input = $request->all();
        $case_fi_type_id = $input['case_fi_id'];
        $case_id = $input['case_id'];
        $cases           = casesFiType::findOrFail($case_fi_type_id);


        $cases->mobile = $request->mobile;

        if (in_array($input['fi_type_id'], [7, 8])) {
            $address = 'address';
            $pincode = 'pincode';
            $landmark = 'land_mark';
            $cases->address = $request->$address ?? '';
            $cases->pincode = $request->$pincode;
            $cases->land_mark = $request->$landmark;
        }

        if (in_array($input['fi_type_id'], [12, 14, 17])) {
            $panNumber = 'pan_number';
            $cases->pan_number = $request->$panNumber;
        }

        if (in_array($input['fi_type_id'], [12, 14])) {
            $assessYear = 'assessment_year';
            $cases->assessment_year = $request->$assessYear;
        }

        if (in_array($input['fi_type_id'], [13])) {
            $accountnumber = 'accountnumber';
            $bankName = 'bank_name';
            $cases->accountnumber = $request->$accountnumber;
            $cases->bank_name = $request->$bankName;
        }

        if (in_array($input['fi_type_id'], [17])) {
            $aadharNumber = 'aadhar_number';
            $cases->aadhar_card = $request->$aadharNumber;
        }

        // $cases->address  = $input['address'] ?? null;
        // $cases->land_mark  = $input['land_mark'] ?? null;
        // $cases->pincode  = $input['pincode'] ?? null;
        $cases->save();

        $case = Cases::findOrFail($case_id);
        $case->applicant_name  = $input['applicant_name'] ?? null;
        // $case->geo_limit  = $input['geo_limit'] ?? null;
        $case->remarks  = $input['remarks'] ?? null;
        // $case->amount  = $input['amount'] ?? null;
        $case->save();

        LogHelper::logActivity('Modify Case', 'User modify case.');
        return response()->json(['success' => 'Case Update successfully !!'], 200);
        // session()->flash('success', 'Case Update successfully !!');
        // return redirect()->back();

    }

    public function getForm($id = null)
    {
        $case = casesFiType::with([
            'getUser',
            'getCase',
            'getBranch',
            'getCaseFiType',
            'getFiType',
            'getStatus',
            'getCaseStatus',
            'getUserVerifiersName'
        ])->where('id', $id)->firstOrFail();
        $fi_type_id = $case->fi_type_id;

        // Handle null or invalid $fi_type_id
        if (is_null($fi_type_id)) {
            return response()->json(['error' => 'Invalid FI Type ID'], 400);
        }

        $fi_type_details = FiType::find($fi_type_id);
        // dd($fi_type_details);
        // Handle null $fi_type_details
        if (is_null($fi_type_details)) {
            return response()->json(['error' => 'FI Type details not found'], 404);
        }
        switch ($fi_type_details->fi_code) {
            case 'bv':
                return view('backend.pages.cases.details-bv', compact('case'))->render();
            case 'rv':
                return view('backend.pages.cases.details-rv', compact('case'))->render();
            case 'itr':
                $caseType = 'ITR Verification Format';
                return view('backend.pages.cases.details-itr', compact('case', 'caseType'))->render();
            case 'form_16':
                $caseType = 'Form 16';
                return view('backend.pages.cases.details-form-16', compact('case', 'caseType'))->render();
            case 'salary ship':
                $caseType = 'Salary sip Format';
                return view('backend.pages.cases.details-itr', compact('case', 'caseType'))->render();
        }

        // Handle specific $fi_type_id cases
        if ($fi_type_id == 17 || $fi_type_id == 13) {
            $caseType = $fi_type_id == 13 ? 'Bankng Verification Format' : 'Pan Verification Format';
            return view('backend.pages.cases.details-pan', compact('case', 'caseType'))->render();
        }

        return response()->json(['error' => 'Unhandled FI Type'], 400);
    }

    public function modifyForm($id = null)
    {
        $case = casesFiType::with(['getUser', 'getCase', 'getCaseFiType', 'getFiType', 'getCaseStatus'])->where('id', $id)->firstOrFail();
        $AvailbleProduct = [];
        if ($case->getCase->bank_id) {
            $AvailbleProduct = Product::select('bpm.id', 'bpm.bank_id', 'bpm.product_id', 'products.name', 'products.product_code')
                ->leftJoin('bank_product_mappings as bpm', 'bpm.product_id', '=', 'products.id')
                ->where('bpm.bank_id', $case->getCase->bank_id)
                ->get();
        }
        $fi_type_id = $case['fi_type_id'];

        $fi_type_details = FiType::find($fi_type_id);
        if ($fi_type_details->fi_code == 'bv') {
            $view = view('backend.pages.cases.modify-bv',  compact('case', 'AvailbleProduct'))->render();
        } else if ($fi_type_details->fi_code == 'rv') {
            $view = view('backend.pages.cases.modify-rv',  compact('case', 'AvailbleProduct'))->render();
        } else if ($fi_type_details->fi_code == 'form_16') {
            $view = view('backend.pages.cases.modify-form-16',  compact('case', 'AvailbleProduct'))->render();
        }
        return response()->json(['viewData' => $view]);
    }

    public function modifyRVCase(Request $request, $id)
    {
        $rules = [
            'case_fi_id' => 'required',
            'amount' => 'required',
        ];
        $request->validate($rules);

        $input = $request->all();
        $case_fi_type_id = $input['case_fi_id'];
        $caseFi = casesFiType::findOrFail($case_fi_type_id);
        $case =  Cases::find($caseFi->case_id);

        $case->refrence_number  = $input['refrence_number'] ?? null;
        $case->applicant_name   = $input['applicant_name'] ?? null;
        $case->save();

        $caseFi->applicant_name                     = $input['applicant_name_cfi'] ?? null;
        $caseFi->dealer_code                        = $input['dealer_code'] ?? null;
        $caseFi->landline                           = $input['landline'] ?? null;
        $caseFi->mobile                             = $input['mobile'] ?? null;
        $caseFi->address                            = $input['address'] ?? null;
        $caseFi->address_confirmed                  = $input['address_confirmed'] ?? null;
        $caseFi->address_confirmed_by               = $input['address_confirmed_by'] ?? null;
        $caseFi->person_met                         = $input['person_met'] ?? null;
        $caseFi->relationship                       = $input['relationship'] ?? null;
        $caseFi->no_of_residents_in_house           = $input['no_of_residents_in_house'] ?? null;
        $caseFi->years_at_current_residence         = $input['years_at_current_residence'] ?? null;
        $caseFi->no_of_earning_family_members       = $input['no_of_earning_family_members'] ?? null;
        $caseFi->residence_status                   = $input['residence_status'] ?? null;
        $caseFi->approx_rent                        = $input['approx_rent'] ?? null;
        $caseFi->latitude                           = $input['latitude'] ?? null;
        $caseFi->longitude                          = $input['longitude'] ?? null;
        $caseFi->latlong_address                    = $input['latlong_address'] ?? null;
        $caseFi->permanent_address                  = $input['permanent_address'] ?? null;
        $caseFi->location                           = $input['location'] ?? null;
        $caseFi->locality                           = $input['locality'] ?? null;
        $caseFi->accommodation_type                 = $input['accommodation_type'] ?? null;
        $caseFi->interior_conditions                = $input['interior_conditions'] ?? null;
        $caseFi->assets_seen                        = $input['assets_seen'] ?? null;
        $caseFi->area                               = $input['area'] ?? null;
        $caseFi->standard_of_living                 = $input['standard_of_living'] ?? null;
        $caseFi->nearest_landmark                   = $input['nearest_landmark'] ?? null;
        $caseFi->locked_relationship                = $input['locked_relationship'] ?? null;
        $caseFi->applicant_age                      = $input['applicant_age'] ?? null;
        $caseFi->residence_status_others            = $input['residence_status_others'] ?? null;
        $caseFi->years_at_current_residence_others  = $input['years_at_current_residence_others'] ?? null;
        $caseFi->occupation                         = $input['occupation'] ?? null;
        // $caseFi->verifiers_name                     = $input['verifiers_name'] ?? null;
        $caseFi->verification_conducted_at          = $input['verification_conducted_at'] ?? null;
        $caseFi->proof_attached                     = $input['proof_attached'] ?? null;
        $caseFi->type_of_proof                      = $input['type_of_proof'] ?? null;
        $caseFi->date_of_visit                      = $input['date_of_visit'] ?? null;
        $caseFi->time_of_visit                      = $input['time_of_visit'] ?? null;
        $caseFi->supervisor_remarks                 = $input['supervisor_remarks'] ?? null;
        $caseFi->app_remarks                        = $input['app_remarks'] ?? null;
        $caseFi->visit_conducted                    = $input['visit_conducted'] ?? 'NO';
        $caseFi->tcp1_name                          = $input['tcp1_name'] ?? null;
        $caseFi->tcp1_checked_with                  = $input['tcp1_checked_with'] ?? null;
        $caseFi->tcp1_negative_comments             = $input['tcp1_negative_comments'] ?? null;
        $caseFi->tcp2_name                          = $input['tcp2_name'] ?? null;
        $caseFi->tcp2_checked_with                  = $input['tcp2_checked_with'] ?? null;
        $caseFi->tcp2_negative_comments             = $input['tcp2_negative_comments'] ?? null;
        $caseFi->visited_by                         = $input['visited_by'] ?? null;
        // $caseFi->verified_by                        = $input['verified_by'] ?? null;
        $caseFi->negative_feedback_reason           = $input['negative_feedback_reason'] ?? null;
        $caseFi->untraceable                        = $input['untraceable'] ?? null;
        $caseFi->reason_of_untraceable              = $input['reason_of_untraceable'] ?? null;
        $caseFi->reason_of_calling                  = $input['reason_of_calling'] ?? null;
        $caseFi->is_applicant_know_to_person        = $input['is_applicant_know_to_person'] ?? null;
        $caseFi->to_whom_does_address_belong        = $input['to_whom_does_address_belong'] ?? null;

        // Save the model
        $caseFi->save();
        session()->flash('success', 'Case Update successfully !!');
        LogHelper::logActivity('Modify RV Case', 'User modify rv case.');
        return response()->json(['success' => 'Case Update successfully !!'], 200);
    }

    public function modifyBVCase(Request $request, $id)
    {
        $input = $request->all();
        $rules = [
            'case_fi_id' => 'required',
            'amount' => 'required',

        ];
        $request->validate($rules);
        $case_fi_type_id = $input['case_fi_id'];
        $caseFi = casesFiType::findOrFail($case_fi_type_id);
        $case =  Cases::find($caseFi->case_id);

        $referenceNumber = $input['refrence_number'] ?? $input['file_number'] ?? null;
        if (!is_null($referenceNumber)) {
            $case->refrence_number = $referenceNumber;
        }
        if (array_key_exists('applicant_name', $input)) {
            $case->applicant_name = $input['applicant_name'];
        }
        $case->save();

        if ($case->bank_id == 12) {
            $caseFi->fcu_agency_name             = $input['fcu_agency_name'] ?? $caseFi->fcu_agency_name;
            $caseFi->rac_name                    = $input['rac_name'] ?? $caseFi->rac_name;
            $caseFi->fcu_location                = $input['fcu_location'] ?? $caseFi->fcu_location;
            $caseFi->file_number                 = $input['file_number'] ?? $caseFi->file_number;
            $caseFi->dsa_code                    = $input['dsa_code'] ?? $caseFi->dsa_code;
            $caseFi->dme                         = $input['dme'] ?? $caseFi->dme;
            $caseFi->sales_manager_code          = $input['sales_manager_code'] ?? $caseFi->sales_manager_code;
            $caseFi->mobile                      = $input['contact_number'] ?? $caseFi->mobile;
            $caseFi->address                     = $input['residence_address'] ?? $caseFi->address;
            $caseFi->residence_address           = $input['residence_address'] ?? $caseFi->residence_address;
            $caseFi->office_name                 = $input['office_name'] ?? $caseFi->office_name;
            $caseFi->employment_status           = $input['employment_status'] ?? $caseFi->employment_status;
            $caseFi->employer_address            = $input['office_address'] ?? $caseFi->employer_address;
            $caseFi->pickup_for                  = $input['pickup_for'] ?? $caseFi->pickup_for;
            $caseFi->pickup_reason               = $input['pickup_reason'] ?? $caseFi->pickup_reason;
            $caseFi->pickup_on                   = $input['pickup_on'] ?? $caseFi->pickup_on;
            $caseFi->report_submitted_on         = $input['report_submitted_on'] ?? $caseFi->report_submitted_on;
            $caseFi->sampler_name                = $input['sampler_name'] ?? $caseFi->sampler_name;
            $caseFi->verifier_name               = $input['verifier_name'] ?? $caseFi->verifier_name;
            $caseFi->fraudulent_document_1       = $input['fraudulent_document_1'] ?? $caseFi->fraudulent_document_1;
            $caseFi->fraudulent_document_2       = $input['fraudulent_document_2'] ?? $caseFi->fraudulent_document_2;
            $caseFi->fraudulent_document_3       = $input['fraudulent_document_3'] ?? $caseFi->fraudulent_document_3;
            $caseFi->fraudulent_profile_1        = $input['fraudulent_profile_1'] ?? $caseFi->fraudulent_profile_1;
            $caseFi->fraudulent_profile_2        = $input['fraudulent_profile_2'] ?? $caseFi->fraudulent_profile_2;
            $caseFi->fraudulent_profile_3        = $input['fraudulent_profile_3'] ?? $caseFi->fraudulent_profile_3;
            $caseFi->observation_setup_company   = $input['observation_setup_company'] ?? $caseFi->observation_setup_company;
            $caseFi->observation_negative_market = $input['observation_negative_market'] ?? $caseFi->observation_negative_market;
            $caseFi->observation_anti_social     = $input['observation_anti_social'] ?? $caseFi->observation_anti_social;
            $caseFi->residence_profile           = $input['residence_profile'] ?? $caseFi->residence_profile;
            $caseFi->verification_status         = $input['verification_status'] ?? $caseFi->verification_status;
            $caseFi->authorized_signatory        = $input['authorized_signatory'] ?? $caseFi->authorized_signatory;
        } else {
            $caseFi->dealer_code                    = $input['dealer_code'] ?? null;
            $caseFi->landline                       = $input['landline'] ?? null;
            $caseFi->mobile                         = $input['mobile'] ?? null;
            $caseFi->address                        = $input['address'] ?? null;
            $caseFi->address_confirmed              = $input['address_confirmed'] ?? null;
            $caseFi->employer_address               = $input['employer_address'] ?? null;
            $caseFi->residence_status               = $input['residence_status'] ?? null;
            $caseFi->type_of_proof                  = $input['type_of_proof'] ?? null;
            $caseFi->applicant_age                  = $input['applicant_age'] ?? null;
            $caseFi->person_met                     = $input['person_met'] ?? null;
            $caseFi->name_of_employer_co            = $input['name_of_employer_co'] ?? null;
            $caseFi->employer_mobile                = $input['employer_mobile'] ?? null;
            $caseFi->co_board_outside_bldg_office   = $input['co_board_outside_bldg_office'] ?? null;
            $caseFi->type_of_employer               = $input['type_of_employer'] ?? null;
            $caseFi->nature_of_business             = $input['nature_of_business'] ?? null;
            $caseFi->designation                    = $input['designation'] ?? null;
            $caseFi->line_of_business               = $input['line_of_business'] ?? null;
            $caseFi->year_of_establishment          = $input['year_of_establishment'] ?? null;
            $caseFi->level_of_business_activity     = $input['level_of_business_activity'] ?? null;
            $caseFi->no_of_employees                = $input['no_of_employees'] ?? null;
            $caseFi->interior_conditions            = $input['interior_conditions'] ?? null;
            $caseFi->type_of_locality               = $input['type_of_locality'] ?? null;
            $caseFi->area                           = $input['area'] ?? null;
            $caseFi->nearest_landmark               = $input['nearest_landmark'] ?? null;
            $caseFi->ease_of_locating               = $input['ease_of_locating'] ?? null;
            $caseFi->grade                          = $input['grade'] ?? null;
            $caseFi->telephono_no_office            = $input['telephono_no_office'] ?? null;
            $caseFi->ext                            = $input['ext'] ?? null;
            $caseFi->established                    = $input['established'] ?? null;
            $caseFi->name_of_employer               = $input['name_of_employer'] ?? null;
            $caseFi->app_remarks                    = $input['app_remarks'] ?? null;
            $caseFi->supervisor_remarks             = $input['supervisor_remarks'] ?? null;
            $caseFi->negative_feedback_reason       = $input['negative_feedback_reason'] ?? null;
            $caseFi->visit_conducted                = $input['visit_conducted'] ?? null;
            $caseFi->date_of_visit                  = $input['date_of_visit'] ?? null;
            $caseFi->time_of_visit                  = $input['time_of_visit'] ?? null;
            $caseFi->nature_of_employer             = $input['nature_of_employer'] ?? null;
            $caseFi->latitude                       = $input['latitude'] ?? null;
            $caseFi->longitude                      = $input['longitude'] ?? null;
            $caseFi->latlong_address                = $input['latlong_address'] ?? null;
            $caseFi->tcp1_name                      = $input['tcp1_name'] ?? null;
            $caseFi->tcp1_checked_with              = $input['tcp1_checked_with'] ?? null;
            $caseFi->tcp1_negative_comments         = $input['tcp1_negative_comments'] ?? null;
            $caseFi->tcp2_name                      = $input['tcp2_name'] ?? null;
            $caseFi->tcp2_checked_with              = $input['tcp2_checked_with'] ?? null;
            $caseFi->tcp2_negative_comments         = $input['tcp2_negative_comments'] ?? null;
            $caseFi->visited_by                     = $input['visited_by'] ?? null;
            // $caseFi->verified_by                    = $input['verified_by'] ?? null;
        }

        $caseFi->save();

        session()->flash('success', 'Case Update successfully !!');
        LogHelper::logActivity('Modify BV Case', 'User modify bv case.');
        return response()->json(['success' => 'Case Update successfully !!'], 200);
    }

    public function modifyForm16Case(Request $request, $id)
    {
        $input = $request->all();

        // Validation rules
        // $rules = [
        //     'case_fi_id' => 'required',
        //     'amount' => 'required',
        //     'agency_name' => 'nullable|string|max:255',
        //     'bank_reference' => 'nullable|string|max:255',
        //     'dda_reference' => 'nullable|string|max:255',
        //     'date_received' => 'nullable|date',
        //     'date_submitted' => 'nullable|date',
        //     'loan_branch' => 'nullable|string|max:255',
        //     'loan_type' => 'nullable|string|max:255',
        //     'zone' => 'nullable|string|max:255',
        //     'applicant_name' => 'nullable|string|max:255',
        //     'employer_name' => 'nullable|string|max:255',
        //     'salary' => 'nullable|numeric',
        //     'other_income' => 'nullable|numeric',
        //     'total_income' => 'nullable|numeric',
        //     'form16_employer' => 'nullable|string|max:255',
        //     'assessment_year' => 'nullable|string|max:10',
        //     'remarks' => 'nullable|string|max:1000',
        // ];

        // $validated = $request->validate($rules);

        // Find case and case_fi_type
        $case_fi_type_id = $input['case_fi_id'];
        $caseFi = casesFiType::findOrFail($case_fi_type_id);
        $case = Cases::find($caseFi->case_id);

        // Update case fields
        $case->applicant_name = $input['applicant_name'] ?? null;
        $case->save();
        
        
        // Add new fields
        $caseFi->applicant_name = $input['applicant_name'] ?? null;
        $caseFi->address = $input['agency_name'] ?? null;
        $caseFi->bank_name = $input['bank_reference'] ?? null;
        $caseFi->dd_ref_no = $input['dda_reference'] ?? null;
        $caseFi->assement_date = $input['date_received'] ?? null;
        $caseFi->date_of_visit = $input['date_submitted'] ?? null;
        $caseFi->branch = $input['loan_branch'] ?? null;
        $caseFi->type_of_employer = $input['loan_type'] ?? null;
        $caseFi->locality = $input['zone'] ?? null;
        $caseFi->name_of_employer = $input['name_of_employer'] ?? null;
        $caseFi->salary = $input['salary'] ?? null;
        $caseFi->other_income = $input['other_income'] ?? null;
        $caseFi->total_income = $input['total_income'] ?? null;
        $caseFi->name_of_employer_co = $input['name_of_employer_co'] ?? null;
        $caseFi->co_applicant = $input['co_applicant'] ?? null;
        $caseFi->guarantor = $input['guarantor'] ?? null;
        $caseFi->assessment_year = $input['assessment_year'] ?? null;
        $caseFi->form16_issued = $input['form16_issued'] ?? null;
        $caseFi->tax_matched = $input['tax_matched'];
        $caseFi->pan_number = $input['pan_number'];
        $caseFi->total_income_verification = $input['total_income_verification'];
        $caseFi->income_amount = $input['income_amount'];
        $caseFi->remarks = $input['remarks'] ?? null;
        $caseFi->consolidated_remarks = $input['consolidated_remarks'];
        $caseFi->negative_feedback_reason = $input['negative_feedback_reason'];
        $caseFi->supervisor_remarks = $input['supervisor_remarks'];
        $caseFi->app_remarks = $input['app_remarks'];
        $caseFi->recommended = $input['recommended'] ?? null;

        // Save updates
        $caseFi->save();

        // Log activity and send success response
        session()->flash('success', 'Case updated successfully !!');
        LogHelper::logActivity('Modify Form 16 Case', 'User modified Form 16 case.');

        return response()->json(['success' => 'Case updated successfully !!'], 200);
    }


    public function zipDownload($case_fi_id = null)
    {
        if (!$case_fi_id) {
            return redirect()->back()->with('error', 'Invalid case ID.');
        }

        try {
            $caseFi = casesFiType::findOrFail($case_fi_id);

            $zip = new ZipArchive;
            $path = storage_path('app/public/');
            $fileName = 'attachment_' . $caseFi->id . '.zip';
            $zipFile = $path . $fileName;

            if ($zip->open($zipFile, ZipArchive::CREATE) === TRUE) {
                // Iterate over the images dynamically
                for ($i = 1; $i <= 9; $i++) {
                    $imageField = 'image_' . $i;

                    if (!empty($caseFi->$imageField) && file_exists(public_path($caseFi->$imageField))) {
                        $relativeName = basename($caseFi->$imageField);
                        $zip->addFile(public_path($caseFi->$imageField), $relativeName);
                    }
                }
                $zip->close();
            }

            if (file_exists($zipFile)) {
                LogHelper::logActivity('Zip Download', 'User downloaded case supported documents as a zip.');
                return response()->download($zipFile)->deleteFileAfterSend(true); // Automatically delete the zip after download
            } else {
                return redirect()->back()->with('error', 'Unable to create zip file.');
            }
        } catch (\Exception $e) {
            Log::error('Error creating zip file: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while creating the zip file.');
        }
    }


    public function generatePdf($id)
    {
        try {
            // Load case with relationships
            $case = casesFiType::with([
                'getUser',
                'getCase',
                'getCase.getBank',
                'getCase.getProduct', 
                'getCase.getBank',
                'getCaseFiType',
                'getFiType',
                'getCaseStatus'
            ])
            ->where('id', $id)
            ->firstOrFail();

            // Generate view based on fi_type_id
            if ($case->fi_type_id == 12) {
                $view = view('backend.pages.cases.itr-pdf', compact('case'))->render();
            } else if ($case->fi_type_id == 17 || $case->fi_type_id == 13) {
                $view = view('backend.pages.cases.pan-pdf', compact('case'))->render();
            } else if ($case->fi_type_id == 7) {
                $view = view('backend.pages.cases.rv-pdf', compact('case'))->render();
            } else if ($case->fi_type_id == 14) {
                $view = view('backend.pages.cases.pdf-form-16', compact('case'))->render();
            } else {
                $view = view('backend.pages.cases.pdf', compact('case'))->render();
            }

            // Configure Dompdf
            $options = new \Dompdf\Options();
            $options->set('isRemoteEnabled', true);
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isPhpEnabled', true);
            $options->set('debugKeepTemp', true);
            $options->set('chroot', public_path());
            $options->set('defaultMediaType', 'screen');
            $options->set('defaultPaperSize', 'A4');
            $options->set('dpi', 96);

            // Create Dompdf instance with options
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($view);

            // Set paper size and orientation with margins
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->set_option('margin-top', '10mm');    
            $dompdf->set_option('margin-right', '15mm');  // Increased right margin
            $dompdf->set_option('margin-bottom', '10mm');
            $dompdf->set_option('margin-left', '15mm');   // Balanced left margin

            // Enable chunk size for large files
            ini_set('memory_limit', '256M');
            set_time_limit(300); // 5 minutes

            // Render PDF
            $dompdf->render();

            // Generate filename
            $fileName = 'case_' . date('Y-m-d_H-i-s') . '.pdf';

            // Get PDF content
            $output = $dompdf->output();

            // Log activity
            LogHelper::logActivity('Print Case', 'User export case as pdf.');

            // Stream file with proper headers
            return response()->streamDownload(
                function() use ($output) {
                    echo $output;
                },
                $fileName,
                [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
                    'Cache-Control' => 'no-cache, no-store, must-revalidate',
                    'Pragma' => 'no-cache',
                    'Expires' => '0'
                ]
            );

        } catch (\Exception $e) {
            // Log error
            \Log::error('PDF Generation Error: ' . $e->getMessage());
            
            // Return error response
            return back()->with('error', 'Error generating PDF: ' . $e->getMessage());
        }
    }

    public function telecallerForm($id = null)
    {
        $case = casesFiType::with(['getUser', 'getCase', 'getCaseFiType', 'getFiType', 'getCaseStatus'])->where('id', $id)->firstOrFail();
        $AvailbleProduct = [];
        if ($case->getCase->bank_id) {
            $AvailbleProduct = Product::select('bpm.id', 'bpm.bank_id', 'bpm.product_id', 'products.name', 'products.product_code')
                ->leftJoin('bank_product_mappings as bpm', 'bpm.product_id', '=', 'products.id')
                ->where('bpm.bank_id', $case->getCase->bank_id)
                ->where('products.status', '1')
                ->get();
        }
        $fi_type_id = $case['fi_type_id'];
        $fi_type_details = FiType::find($fi_type_id);
        $view = view('backend.pages.cases.caller',  compact('case', 'AvailbleProduct'))->render();
        return response()->json(['viewData' => $view]);
    }

    public function telecallerSubmit(Request $request, $id)
    {
        $input =  $request->all();
        $caseFi = casesFiType::with(['getUser', 'getCase', 'getCaseFiType', 'getFiType', 'getCaseStatus'])->where('id', $id)->firstOrFail();
        $case =  Cases::find($caseFi->case_id);
        $case->refrence_number = $input['refrence_number'] ?? null;
        $case->applicant_name = $input['applicant_name'] ?? null;
        $case->save();
        $caseFi->mobile        = $input['mobile'] ?? null;
        $caseFi->address       = $input['address'] ?? null;
        $caseFi->person_met    = $input['person_met'] ?? null;
        $caseFi->relationship  = $input['relationship'] ?? null;
        $caseFi->applicant_age = $input['applicant_age'] ?? null;
        $caseFi->designation   = $input['designation'] ?? null;
        $caseFi->remarks       = $input['remarks'] ?? null;
        $caseFi->save();
        session()->flash('success', 'Case Update successfully !!');
        LogHelper::logActivity('Telecaller Case Update', 'Telecaller Case Update.');
        return redirect()->back();
    }

    public function sendCaseNotificaton($id = null)
    {
        $case = casesFiType::with(['getUser', 'getCase', 'getCaseFiType', 'getFiType', 'getCaseStatus'])->where('id', $id)->firstOrFail();
        $AvailbleProduct = [];
        if ($case->getCase->bank_id) {
            $AvailbleProduct = Product::select('bpm.id', 'bpm.bank_id', 'bpm.product_id', 'products.name', 'products.product_code')
                ->leftJoin('bank_product_mappings as bpm', 'bpm.product_id', '=', 'products.id')
                ->where('bpm.bank_id', $case->getCase->bank_id)
                ->where('products.status', '1')
                ->get();
        }
        $fi_type_id = $case['fi_type_id'];
        $fi_type_details = FiType::find($fi_type_id);
        $view = view('backend.pages.cases.notify',  compact('case', 'AvailbleProduct'))->render();
        return response()->json(['viewData' => $view]);
    }

    public function sendCaseNotificatonSubmit(Request $request, $id)
    {
        $input =  $request->all();
        $caseFi = casesFiType::with(['getUser', 'getCase', 'getCaseFiType', 'getFiType', 'getCaseStatus'])->where('id', $id)->firstOrFail();
        $details = ['body' => 'Your Case detail ink is ' . route('home.caseDetail', $id), 'from' => 'info@intelisysweb.com', 'subject' => 'Verification Status'];
        Mail::to('susheelcs0024@gmail.com')->send(new SendMail($details));
        session()->flash('success', 'Email sent successfully !!');
        return redirect()->back();
    }
}
