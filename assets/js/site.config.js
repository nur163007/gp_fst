/*
 Author: Shohel Iqbal
 Copyright: 01.2016
 Code fridged on:
*/

// Role Constraint
var _buyer = 2;
var _supplier = 3;

const ACTION_NEW_PO_INITIATED = 1;
const ACTION_PO_REJECTED_BY_SUPPLIER = 2;
const ACTION_REVISED_PO_SENT = 3;
const ACTION_DRAFT_PI_SUBMITTED = 4;
const ACTION_DRAFT_PI_SENT_FOR_PR_FEEDBACK = 5;
const ACTION_DRAFT_PI_SENT_FOR_EA_FEEDBACK = 6;
const ACTION_DRAFT_PI_REJECTED_BY_PR = 7;
const ACTION_DRAFT_PI_ACCEPTED_BY_PR = 8;
const ACTION_DRAFT_PI_REJECTED_BY_EA = 9;
const ACTION_DRAFT_PI_ACCEPTED_BY_EA = 10;
const ACTION_REQUESTED_FOR_DRAFT_PI_RECTIFICATION = 11;
const ACTION_REQUESTED_FOR_FINAL_PI = 12;
const ACTION_FINAL_PI_SUBMITTED = 13;
const ACTION_FINAL_PI_SENT_FOR_PR_FEEDBACK = 14;
const ACTION_FINAL_PI_SENT_FOR_EA_FEEDBACK = 15;
const ACTION_FINAL_PI_REJECTED_BY_PR = 16;
const ACTION_FINAL_PI_ACCEPTED_BY_PR = 17;
const ACTION_FINAL_PI_REJECTED_BY_EA = 18;
const ACTION_FINAL_PI_ACCEPTED_BY_EA = 19;
const ACTION_REQUESTED_FOR_FINAL_PI_RECTIFICATION = 20;
const ACTION_FINAL_PI_ACCEPTED = 21;
const ACTION_SENT_FOR_BTRC_PERMISSION = 22;
const ACTION_REJECTED_BY_BTRC = 23;
const ACTION_ACCEPTED_BY_BTRC = 24;
//-------------Start E delivery flow----------13.01.2022---
const ACTION_FINAL_PI_ACCEPTED_EDELIVERY_WITH_LC = 109;
const ACTION_REQUEST_FOR_BANK_FORWARDING_LETTER = 110;
const ACTION_REQUEST_FOR_BASIS_APPROVAL_LETTER = 111;
const ACTION_BASIS_APPROVAL_LETTER_SENT_BY_BANK = 112;
const ACTION_BASIS_APPROVAL_LETTER_SHARED_TO_BUYER = 113;
const ACTION_REQUEST_FOR_DOC_ENDORSEMENT_SEND_BY_GP = 117;
const ACTION_DOC_ENDORSEMENT_SEND_BY_BANK = 118;
//-------------End E delivery flow-------------------------
const ACTION_LC_REQUEST_SENT = 25;
const ACTION_REJECTED_BY_1ST_LEVEL = 26;
const ACTION_SENT_REVISED_LC_REQUEST_1 = 27;
const ACTION_APPROVED_BY_1ST_LEVEL = 28;
const ACTION_REJECTED_BY_2ND_LEVEL = 29;
const ACTION_SENT_REVISED_LC_REQUEST_2 = 30;
const ACTION_APPROVED_BY_2ND_LEVEL = 31;
const ACTION_REJECTED_BY_3RD_LEVEL = 32;
const ACTION_SENT_REVISED_LC_REQUEST_3 = 33;
const ACTION_APPROVED_BY_3RD_LEVEL = 34;
const ACTION_REJECTED_BY_4TH_LEVEL = 35;
const ACTION_SENT_REVISED_LC_REQUEST_4 = 36;
const ACTION_APPROVED_BY_4TH_LEVEL = 37;
const ACTION_REJECTED_BY_5TH_LEVEL = 38;
const ACTION_SENT_REVISED_LC_REQUEST_5 = 39;
const ACTION_APPROVED_BY_5TH_LEVEL = 40;
const ACTION_FINAL_LC_COPY_SENT = 41;
const ACTION_REQUESTED_FOR_LC_AMENDMENT = 42;
const ACTION_REJECTED_AMENDMENT_REQUEST = 43;
const ACTION_ACCEPTED_AMENDMENT_REQUEST = 44;
const ACTION_AMENDMENT_REQUEST_REJECTED_BY_2ND_LEVEL = 45;
const ACTION_REVISED_LC_AMENDMENT_SENT_2 = 46;
const ACTION_AMENDMENT_REQUEST_APPROVED_BY_2ND_LEVEL = 47;
const ACTION_AMENDMENT_REQUEST_REJECTED_BY_4TH_LEVEL = 48;
const ACTION_REVISED_LC_AMENDMENT_SENT_4 = 49;
const ACTION_AMENDMENT_REQUEST_APPROVED_BY_4TH_LEVEL = 50;
const ACTION_AMENDMENT_REQUEST_REJECTED_BY_5TH_LEVEL = 51;
const ACTION_REVISED_LC_AMENDMENT_SENT_5 = 52;
const ACTION_AMENDMENT_REQUEST_APPROVED_BY_5TH_LEVEL = 53;
const ACTION_AMENDMENT_COPY_SENT = 54;
const ACTION_LC_ACCEPTED = 55;
const ACTION_SHARED_SHIPMENT_SCHEDULE = 56;
const ACTION_REJECTED_SHIPMENT_SCHEDULE = 57;
const ACTION_ACCEPTED_SHIPMENT_SCHEDULE = 58;
const ACTION_SHARED_SHIPMENT_DOCUMENT = 59;
const ACTION_SHIPMENT_DOCUMENT_REJECTED = 60;
const ACTION_REQUESTED_FOR_WAREHOUSE_INPUTS = 61;
const ACTION_SHIP_DOC_REJECTED_WAREHOUSE = 62;
const ACTION_WAREHOUSE_INPUT_UPDATED_PENDING_FN = 63;
const ACTION_REQUESTED_FOR_EA_INPUTS = 64;
const ACTION_SENT_FOR_ORIGINAL_DOCUMENT_ACCPETANCE = 65;
const ACTION_ORIGINAL_DOCUMENT_REJECTED = 66;
const ACTION_ORIGINAL_DOCUMENT_ACCEPTED_BY_EA = 67;
const ACTION_ORIGINAL_DOCUMENT_ACCEPTED_FOR_DOCUMENT_DELIVERY = 68;
const ACTION_ORIGINAL_DOCUMENT_DELIVERED = 69;
const ACTION_SENT_FOR_DOCUMENT_ENDORSEMENT = 70;
const ACTION_ENDORSED_DOCUMENT_DELIVERED = 71;
const ACTION_REQUESTED_TO_COLLECT_ORIGINAL_DOC = 72;
const ACTION_GIT_RECEIVING_DATE_UPDATED = 73;
const ACTION_CD_BE_COPY_UPDATED = 74;
const ACTION_CD_PAYMENT_UPDATED_BY_FIN = 75;
const ACTION_AVG_COST_CAL_DONE_BY_FIN = 76;
const ACTION_EDIT_AND_SEND_FOR_RECHECK = 77;
const ACTION_BTRC_PROCESS_APPROVED_BY_3RD_LEVEL = 78;
const ACTION_BTRC_PROCESS_REJECTED_BY_3RD_LEVEL = 79;
const ACTION_SHIP_DOC_SHARED_DHL_TRACK_PENDING = 80;
const ACTION_DHL_TRACK_NO_UPDATED = 81;
const ACTION_SHIP_DOC_ACCEPTED_BUYER_PENDING_WH = 82;
const ACTION_SHIP_DOC_ACCEPTED_BUYER_PENDING_EA = 83;
const ACTION_SHIP_DOC_REJECTED_EATEAM = 84;
const ACTION_WAREHOUSE_INPUT_UPDATED_PENDING_AVG_COST = 85;
const ACTION_AVG_COST_DATA_UPDATED = 86;
const ACTION_TENTATIVE_DELIVERY_DATE_UPDATED = 87;
const ACTION_CD_BE_REJECTED_BY_FIN = 88;
const ACTION_SHARED_VOUCHER_INFO_TO_FIN = 89;
const ACTION_SHIPPING_DOC_RECTIFIED_BY_SUPPLIER = 90;
const ACTION_SENT_TO_BTRC_FOR_NOC = 91;
const action_ea_inputs_completed = 92;

const ACTION_SIGHT_PAYMENT_DONE_BY_FIN = 93;
const ACTION_TAC_REQUEST_SEND_BY_SUPPLIER = 94;
const ACTION_TAC_APPROVED_BY_PRUSER = 95;
const ACTION_TAC_REJECT_BY_PRUSER = 96;
const ACTION_TAC_APPROVED_BY_BUYER = 97;
const ACTION_TAC_REJECTED_BY_BUYER = 98;
const ACTION_TAC_APPROVED_BY_CPO = 99;
const ACTION_TAC_REJECTED_BY_CPO = 100;
const ACTION_TAC_REQUEST_RECTIFIED_BY_SUPPLIER = 101;
const ACTION_REQUEST_FOR_CNF_INPUT = 104;

const ACTION_PO_EDITED_BY_BUYER = 149;
const ACTION_CLOSE_PO = 150;


const ACTION_BCS_EX_SENT_TO_FSO = 201;


const ACTION_COVER_NOTE_REQUESTED_BY_TFO = 301;
const ACTION_COVER_NOTE_SUBMITTED_BY_IC = 302;
const ACTION_COVER_NOTE_ACCEPTED_BY_TFO = 303;
const ACTION_REQUEST_FOR_INS_POLICY_BY_TFO = 310;

const ACTION_DRAFT_LC_REQUEST_SENT_TO_BANK = 401;
const ACTION_FINAL_LC_REQUEST_SENT_TO_BANK = 402;
const ACTION_FEEDBACK_GIVEN_BY_BUYER = 407;
const ACTION_FINAL_LC_COPY_SENT_TO_GP = 404;
const ACTION_BUYER_SUPPLIER_FEEDBACK_ACCEPTED = 409;
// User Role
const const_role_Admin = 1;
const const_role_Buyer = 2;
const const_role_Supplier = 3;
const const_role_External_Approval = 4;
const const_role_Corporate_Affairs = 5;
const const_role_LC_Approvar_1 = 6;
const const_role_LC_Approvar_2 = 7;
const const_role_LC_Approvar_3 = 8;
const const_role_LC_Approvar_4 = 9;
const const_role_LC_Approvar_5 = 10;
const const_role_LC_Operation = 11;
const const_role_LC_Ops_data_entry = 12;
const const_role_Warehouse = 13;
const const_role_Management = 14;
const const_role_PR_Users = 15;
const const_role_cert_final_approver = 16;

const const_role_Report_Viewer = 17;
const const_role_LC_Report_Viewer = 18;
const const_role_foreign_payment_team = 19;
const const_role_foreign_strategy = 20;
const const_role_public_regulatory_affairs = 21;
const const_role_insurance_company = 22;
const const_role_lc_bank = 23;
const const_role_head_of_treasury = 24;
const const_role_bank_fx = 25;
const const_role_cnf_agent = 26;
const const_role_coupa_user = 27;

// Report constraint
const report_buyer_wise_report = 1;

const CONST_FILETYPE_ALL = 'jpg|jpeg|xlsx|xls|doc|docx|pdf|zip';
const CONST_FILETYPE_JPG = 'jpg|jpeg';
const CONST_FILETYPE_WORD = 'doc|docx';
const CONST_FILETYPE_EXCEL = 'xlsx|xls';
const CONST_FILETYPE_PDF = 'pdf|zip';
const CONST_FILETYPE_ZIP = 'zip';


const const_charge_type_capex = 28;
const const_charge_type_opex = 29;
const const_charge_type_insurance = 30;

const docref_document_acceptance_letter_ref = "Finance/Trade Operation/DOC Acc/";
const docref_payment_instruction_letter_ref = "Finance/Trade Operation/PAY Ins/";
const docref_bank_instruction_LCA_letter_ref = "Finance/Trade Operation/LCA Ope/";
const docref_bank_instruction_LC_letter_ref = "Finance/Trade Operation/LC Ope/";
const docref_LC_amendment_instruction_letter_ref = "Finance/Trade Operation/LC Amd/";
const docref_LC_endorsement_letter_ref = "Finance/Trade Operation/LC End/";
const docref_custom_duty_ctg_letter_ref = "Finance/Trade Operation/CD-CTG/";
const docref_custom_duty_dhk_letter_ref = "Finance/Trade Operation/CD-DHK/";
const docref_custom_pra_letter_ref = "No. GP/RO/BTRC/APP/";

const const_defaultImage = "assets/images/no-image.png";

const btrc_division_ENO = 115;
const btrc_division_SM = 116;