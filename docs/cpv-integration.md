# FlexiLoans CPV API Checklist

## Collect Agency Inputs
- Obtain UAT and production base URLs for the agency Create Order API (HTTPS only).
- Confirm request headers or auth requirements (keys, tokens, basic credentials).
- Record the `cpv_agency_name` identifier provided by FlexiLoans for routing callbacks.

## Send Create Order Requests
- Method and path supplied by agency; call over HTTPS with JSON payload.
- Include FlexiLoans identifiers (caseId, fileNo) plus borrower/order data agreed with the agency.
- Capture and log the full response for traceability and retries.

## Receive Callback Reports
- Callback URL pattern: https://documents.flexiloans.com/agency/v1/orders/manifest/{cpv_agency_name}/activity (production) or https://documents.flexiloans.net/agency/v1/orders/manifest/{cpv_agency_name}/activity (staging).
- Expect headers: consignee_code=EpiMoney and Content-Type=application/json.
- Expected body (example):
```
{
  "success": true,
  "message": "CPV verification report generated successfully",
  "data": {
    "caseId": "6718bbda95afac8d88553972",
    "fileNo": "FCLEP-1737034617298",
    "caseStatus": "Positive",
    "verificationType": "Residence/Office",
    "dateTimeOfAllocation": "2024-10-23T09:03:22.416Z",
    "dateTimeOfReport": "2024-10-23T09:03:22.416Z",
    "comments": "Completed the verification",
    "reportLink": "https://{agency-domain}/reports/FCLEP-1737034617298.pdf"
  }
}
```
- Validate caseId/fileNo against the originating request and ensure reportLink remains downloadable.

## Laravel Work Items (pending implementation)
- Add configuration file for per-agency endpoints, headers, auth, and default environment selection.
- Build a service class responsible for Create Order API calls with timeout handling and logging.
- Expose a POST route for callbacks that validates payloads, updates case records, and stores report metadata.
- Enforce security via header checks, optional shared secret, and IP allow lists where available.
- Persist audit logs for all outbound and inbound traffic (status codes, payload snippets, timestamps).

## Testing Checklist
- Run end-to-end tests in agency UAT with sample cases.
- Test negative scenarios (invalid payload, inconsistent IDs, missing reportLink).
- Verify performance and retry behaviour for both outbound calls and inbound callbacks.
- Monitor callback failures and report download errors in production dashboards.
