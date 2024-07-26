<?php

/***************************************************************************************************
 * Copyright (c) 2020. by Camwel Corporate Solution PVT LTD
 * This project is developed and maintained by Camwel Corporate Solution PVT LTD.
 * Nobody is permitted to modify the source or any part of the project without permission.
 * Project Developer: Camwel Corporate Solution PVT LTD
 * Developed for: Camwel Corporate Solution PVT LTD
 **************************************************************************************************/

defined('BASEPATH') or exit('No direct script access allowed');

class Wallet extends CI_Controller
{
    /**
     * Income Section for Admin Only
     */
    public function __construct()
    {
        parent::__construct();
        if ($this->login->check_session() == FALSE && $this->login->check_member() == FALSE) {
            redirect(site_url('site/login'));
        }
        $this->load->model("Earning");
        $this->load->library('pagination');
    }

    public function manage_wallet_fund()
    {
        if ($this->login->check_session() == FALSE) {
            exit('<h3 align="center">You are smelling rotten ! Go and have a bath..</h3>');
        }
        $this->form_validation->set_rules('uid', 'User ID', 'trim|required');
        $this->form_validation->set_rules('balance', 'Wallet Balance', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $data['title']      = 'Manage Wallet Funds';
            $data['breadcrumb'] = 'Wallet Funds';
            $data['layout']     = 'wallet/manage_funds.php';
            $this->load->view('admin/base', $data);
        } else {
            $uid     = $this->common_model->filter($this->input->post('uid'));
            $balance = $this->input->post('balance');
            $type    = $this->input->post('submit');

            $get_fund = $this->db_model->select('balance', 'wallet', array('userid' => $uid));
            $new_fund = $get_fund + $balance;
            if ($type == "remove") {
                $new_fund = $get_fund - $balance;
            }

            $array = array(
                'balance' => $new_fund,
            );
            $this->db->where('userid', $uid);
            $this->db->update('wallet', $array);

            //  =========wallet transection report start ==========
            $data = array(
                'userid' => $uid,
                'amount' => $balance,
                'type'   => ($type == "remove") ? "DR" : "CR",
                'remark' => ($type == "remove") ? "Debited By Admin" : "Credited By admin",
                'date'   => date('Y-m-d')
            );
            $this->db->insert('wallet_transection_report', $data);
            //  =========wallet transection report end ==========

            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Wallet Balance Updated.</div>');
            redirect('wallet/manage_wallet_fund');
        }
    }

    public function transfer_fund()
    {
        if ($this->login->check_session() == FALSE) {
            exit('<h3 align="center">You are smelling rotten ! Go and have a bath..</h3>');
        }
        $this->form_validation->set_rules('userid', 'User ID', 'trim|required');
        $this->form_validation->set_rules('transferid', 'Transfer ID', 'trim|required');
        $this->form_validation->set_rules('amount', 'Amount', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $data['title']      = 'Transfer Wallet Funds';
            $data['breadcrumb'] = 'Transfer Funds';
            $data['layout']     = 'wallet/transfer_funds.php';
            $this->load->view('admin/base', $data);
        } else {
            $uid        = $this->common_model->filter($this->input->post('userid'));
            $transferid = $this->common_model->filter($this->input->post('transferid'));
            $balance    = $this->input->post('amount');

            $get_fund_uid = $this->db_model->select('balance', 'wallet', array('userid' => $uid));
            $get_fund_tid = $this->db_model->select('balance', 'wallet', array('userid' => $transferid));
            if ($get_fund_uid < $balance || $balance <= 0) {
                $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">User donot have sufficient balance in his/her wallet.</div>');
                redirect('wallet/transfer_fund');
            }
            $new_fund = $get_fund_tid + $balance;
            $array    = array(
                'balance' => $new_fund,
            );
            $this->db->where('userid', $transferid);
            $this->db->update('wallet', $array);

            $array = array(
                'balance' => ($get_fund_uid - $balance),
            );
            $this->db->where('userid', $uid);
            $this->db->update('wallet', $array);

            $data = array(
                'transfer_from' => $uid,
                'transfer_to'   => $transferid,
                'amount'        => $balance,
                'time'          => date('Y-m-d'),
            );
            $this->db->insert('transfer_balance_records', $data);

            //  =========wallet transection report start ==========
            $data = array(
                'userid' => $uid,
                'amount' => $balance,
                'type'   => "DR",
                'remark' => "Transfer to&nbsp;" . config_item('ID_EXT') . $transferid . "By Admin",
                'date'   => date('Y-m-d')
            );
            $this->db->insert('wallet_transection_report', $data);

            $data = array(
                'userid' => $transferid,
                'amount' => $balance,
                'type'   => "CR",
                'remark' => "Transfer By&nbsp" . config_item('ID_EXT') . $uid,
                'date'   => date('Y-m-d')
            );
            $this->db->insert('wallet_transection_report', $data);
            //  =========wallet transection report end ==========
            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Fund Transferred Successfully.</div>');
            redirect('wallet/transfer_fund');
        }
    }

    public function withdraw_fund()
    {
        if ($this->login->check_session() == FALSE) {
            exit('<h3 align="center">You are smelling rotten ! Go and have a bath..</h3>');
        }
        $this->form_validation->set_rules('userid', 'User ID', 'trim|required');
        $this->form_validation->set_rules('amount', 'Amount', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $data['title']      = 'Withdraw Wallet Funds';
            $data['breadcrumb'] = 'Withdraw Funds';
            $data['layout']     = 'wallet/withdraw_fund.php';
            $this->load->view('admin/base', $data);
        } else {
            $uid     = $this->common_model->filter($this->input->post('userid'));
            $balance = $this->input->post('amount');

            $get_fund_uid = $this->db_model->select('balance', 'wallet', array('userid' => $uid));
            if ($get_fund_uid < $balance || $balance <= 0) {
                $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">User donot have sufficient balance in his/her wallet.</div>');
                redirect('wallet/withdraw_fund');
            }
            $new_fund = $get_fund_uid - $balance;
            $array    = array(
                'balance' => $new_fund,
            );
            $this->db->where('userid', $uid);
            $this->db->update('wallet', $array);

            $data = array(
                'userid' => $uid,
                'amount' => $balance,
                'date'   => date('Y-m-d'),
            );
            $this->db->insert('withdraw_request', $data);

            //  =========wallet transection report start ==========
            $data = array(
                'userid' => $uid,
                'amount' => $balance,
                'type'   => "DR",
                'remark' => "Withdraw Request Generated By Admin",
                'date'   => date('Y-m-d')
            );
            $this->db->insert('wallet_transection_report', $data);
            //  =========wallet transection report end ==========
            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Fund Withdrawn Successfully.</div>');
            redirect('wallet/withdraw_fund');
        }
    }

    public function wallet_transactions()
    {
        if ($this->login->check_session() == FALSE) {
            exit('<h3 align="center">You are smelling rotten ! Go and have a bath..</h3>');
        }
        $top_id = $this->common_model->filter($this->input->post('top_id'));
        if (trim($top_id) == "") :
            $data['title']      = 'Wallet Transactions';
            $data['breadcrumb'] = 'Wallet Transactions';
            $data['layout']     = 'wallet/wallet_transactions.php';
            $this->load->view('admin/base', $data);

        else :
            if (trim($this->session->user_id) !== "" && $top_id < $this->session->user_id) {
                $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">You cannot view upline Detail !</div>');
                redirect('wallet/wallet_transactions/');
            }
            redirect(site_url('wallet/wallet_transactions/' . $top_id));
        endif;
    }

    public function withdrawl_report()
    {
        if ($this->login->check_session() == FALSE) {
            exit('<h3 align="center">You are smelling rotten ! Go and have a bath..</h3>');
        }
        $top_id = $this->common_model->filter($this->input->post('top_id'));
        $status = $this->input->post('status');
        $sdate  = $this->input->post('sdate');
        $edate  = $this->input->post('edate');
        if (trim($top_id) == "") :
            $data['title']      = 'Withdrawal Report';
            $data['breadcrumb'] = 'Withdrawal Report';
            $data['layout']     = 'wallet/withdrawl_report.php';
            $this->load->view('admin/base', $data);

        else :
            if (trim($this->session->user_id) !== "" && $top_id < $this->session->user_id) {
                $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">You cannot view upline Detail !</div>');
                redirect('wallet/withdrawl_report/');
            }
            redirect(site_url('wallet/withdrawl_report/' . $top_id . '/' . $status . '/' . $sdate . '/' . $edate));
        endif;
    }

    public function generate_payout()
    {
        if ($this->login->check_session() == FALSE) {
            exit('<h3 align="center">You are smelling rotten ! Go and have a bath..</h3>');
        }
        $old_password = $this->input->post('password');
        if (trim($old_password) == "") :
            $data['title']      = 'Generate Payout';
            $data['breadcrumb'] = 'Generate Payout';
            $data['layout']     = 'wallet/generate_payout.php';
            $this->load->view('admin/base', $data);

        else :
            $original_pass = $this->db_model->select('password', 'admin', array('id' => $this->session->admin_id));
            if (password_verify($old_password, $original_pass) == FALSE) {
                $this->session->set_flashdata("common_flash", "<div class='alert alert-danger'>Entered Current Password is wrong.</div>");
                redirect(site_url('wallet/generate_payout'));
            }
            ################ We will generate payout now ################

            $this->db->select('userid, balance,without_tax_bal')->where('balance >=', config_item('min_withdraw'));
            $res = $this->db->get('wallet')->result();
            foreach ($res as $result) {
                $e       = 1;
                $uid     = $result->userid;
                $balance = $result->balance;
                $without_tax_bal = $result->without_tax_bal;

                $array = array(
                    'balance' => 0,
                    'without_tax_bal' => 0
                );
                $this->db->where('userid', $uid);
                $this->db->update('wallet', $array);

                $data = array(
                    'userid' => $uid,
                    'amount' => $balance,
                    'without_tax_amt' => $without_tax_bal,
                    'date'   => date('Y-m-d'),
                );
                $this->db->insert('withdraw_request', $data);

                //  =========wallet transection report start ==========
                $data = array(
                    'userid' => $uid,
                    'amount' => $balance,
                    'type'   => "DR",
                    'remark' => "Withdraw Request Generated By Admin",
                    'date'   => date('Y-m-d')
                );
                $this->db->insert('wallet_transection_report', $data);
                //  =========wallet transection report end ==========

                //echo $this->db->last_query();die;
                $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Payout Generated Successfully.</div>');
            }
            if ($e !== 1) {
                $this->session->set_flashdata('common_flash', '<div class="alert alert-info">No User Id has sufficient balance, Hence No Payout Generated.</div>');
            }
            redirect('income/make-payment');

        #############################################################
        endif;
    }


    ############################## MEMBER SECTION HERE ###########################################

    public function transfer_balance()
    {
        $this->form_validation->set_rules('transferid', 'Transfer ID', 'trim|required');
        $this->form_validation->set_rules('amount', 'Amount', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $data['title']      = 'Transfer Wallet Funds';
            $elig = $this->Earning->check_donation_eligibility();
            $data['tran_pin_have_or_not'] = $this->db_model->get_data('member', array('id' => $this->session->user_id), 'id,transection_pin');
            $data['redonate'] = $elig['redonate'];
            $data['breadcrumb'] = 'Transfer Funds';
            $data['layout']     = 'wallet/transfer_funds.php';
            $this->load->view('member/base', $data);
        } else {
            $uid        = $this->session->user_id;
            $transferid = $this->common_model->filter($this->input->post('transferid'));
            $balance    = $this->input->post('amount');
            $transection_pin    = $this->input->post('transection_pin');

            if ($this->db_model->get_data('member', array('id' => $uid, 'transection_pin' =>       $transection_pin), 'id,transection_pin')) {
                $get_fund_uid = $this->db_model->select('balance', 'wallet', array('userid' => $uid));
                $get_fund_tid = $this->db_model->select('balance', 'wallet', array('userid' => $transferid));
            } else {
                $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Oops, your transaction PIN did not match. Please try again.</div>');
                redirect('wallet/transfer_balance');
            }

            $get_fund_uid = $this->db_model->select('balance', 'wallet', array('userid' => $uid));
            $get_fund_tid = $this->db_model->select('balance', 'wallet', array('userid' => $transferid));

            if ($get_fund_uid < $balance || $balance <= 0) {
                $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">User donot have sufficient balance in your wallet.</div>');
                redirect('wallet/transfer_balance');
            }

            $count_sponsor = $this->db->select('*')->where('sponsor', $uid)->get('member')->num_rows();
            if ($count_sponsor < 2) {
                $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Minimum Sponsor Two member For Amount Transfer | You Only sponsor ' . $count_sponsor . ' Member</div>');
                redirect('wallet/transfer_balance');
            }



            $new_fund = $get_fund_tid + $balance;
            $array    = array(
                'balance' => $new_fund,
            );
            $this->db->where('userid', $transferid);
            $this->db->update('wallet', $array);

            $array = array(
                'balance' => ($get_fund_uid - $balance),
            );
            $this->db->where('userid', $uid);
            $this->db->update('wallet', $array);

            $data = array(
                'transfer_from' => $uid,
                'transfer_to'   => $transferid,
                'amount'        => $balance,
                'time'          => date('Y-m-d'),
            );
            $this->db->insert('transfer_balance_records', $data);

            //  =========wallet transection report start ==========
            $data = array(
                'userid' => $uid,
                'amount' => $balance,
                'type'   => "DR",
                'remark' => "Transfer to&nbsp;" . config_item('ID_EXT') . $transferid,
                'date'   => date('Y-m-d')
            );
            $this->db->insert('wallet_transection_report', $data);

            $data = array(
                'userid' => $transferid,
                'amount' => $balance,
                'type'   => "CR",
                'remark' => "Transfer By&nbsp;" . config_item('ID_EXT') . $uid,
                'date'   => date('Y-m-d')
            );
            $this->db->insert('wallet_transection_report', $data);
            //  =========wallet transection report end ==========


            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Fund Transferred Successfully.</div>');
            redirect('wallet/transfer_balance');
        }
    }

    public function withdrawal_list()
    {
        $status = $this->input->post('status');
        $sdate  = $this->input->post('sdate');
        $edate  = $this->input->post('edate');
        if (trim($status) == "") :
            $data['title']      = 'Withdrawal Report';
            $data['breadcrumb'] = 'Withdrawal Report';
            $data['layout']     = 'wallet/withdrawl_report.php';
            $this->load->view('member/base', $data);

        else :
            redirect(site_url('wallet/withdrawal_list/' . $status . '/' . $sdate . '/' . $edate));
        endif;
    }

    public function test()
    {
       if( $this->db_model->get_data('member', array('id' => $this->session->user_id), 'id,transection_pin'))
       {
        echo "hello succcess";
       }else{
        echo "hello error";
       }
    }

    public function withdraw_payouts()
    {
        $this->form_validation->set_rules('amount', 'Amount', 'trim|required|greater_than_equal_to[' . config_item('min_withdraw') . ']');
        if ($this->form_validation->run() == FALSE) {

            $elig = $this->Earning->check_donation_eligibility();
            $data['redonate'] = $elig['redonate'];
            $data['tranPin_have_or_not'] = $this->db_model->get_data('member', array('id' => $this->session->user_id), 'id,transection_pin');
            $data['title']      = 'Withdraw Wallet Funds';
            $data['breadcrumb'] = 'Withdraw Funds';
            $data['layout']     = 'wallet/withdraw_fund.php';
            $this->load->view('member/base', $data);
        } else {
            $uid     = $this->session->user_id;
            $balance = $this->input->post('amount');
            $transection_pin = $this->input->post('transection_pin');

            if ($this->db_model->get_data('member', array('transection_pin' => $transection_pin), 'id,transection_pin')) {
                $get_fund_uid = $this->db_model->select('balance', 'wallet', array('userid' => $uid));
            } else {
                $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Oops, your transaction PIN did not match. Please try again.</div>');
                redirect('wallet/withdraw_payouts');
            }

            if ($get_fund_uid < $balance || $balance < config_item('min_withdraw')) {
                $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">User donot have sufficient balance in his/her wallet. Use have to withdraw minimum: ' . config_item('currency') . config_item('min_withdraw') . '</div>');
                redirect('wallet/withdraw_payouts');
            }

            $count_sponsor = $this->db->select('*')->where('sponsor', $uid)->get('member')->num_rows();
            if ($count_sponsor < 2) {
                $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Minimum Sponsor Two member For requesting Payout| You Only sponsor ' . $count_sponsor . ' Member</div>');
                redirect('wallet/withdraw_payouts');
            }

            $new_fund = $get_fund_uid - $balance;
            $array    = array(
                'balance' => $new_fund,
            );
            $this->db->where('userid', $uid);
            $this->db->update('wallet', $array);
            $tax = config_item('payout_tax') + config_item('payout_admin_tax');
            $without_tax_amt = $balance - ($balance * $tax) / 100;
            $data = array(
                'userid' => $uid,
                'amount' => $balance,
                'without_tax_amt' => $without_tax_amt,
                'date'   => date('Y-m-d'),
            );
            $this->db->insert('withdraw_request', $data);

            //  =========wallet transection report start ==========
            $data = array(
                'userid' => $uid,
                'amount' => $balance,
                'type'   => "DR",
                'remark' => "PayOut withdraw requested",
                'date'   => date('Y-m-d')
            );
            $this->db->insert('wallet_transection_report', $data);
            //  =========wallet transection report end ==========
            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Fund Withdrawn Successfully.</div>');
            redirect('wallet/withdraw_payouts');
        }
    }

 
    public function balance_transfer_list()
    {
        $data['title']      = 'Wallet Transactions';
        $data['breadcrumb'] = 'Wallet Transactions';
        $data['layout']     = 'wallet/wallet_transactions.php';
        $this->load->view('member/base', $data);
    }

  
}
