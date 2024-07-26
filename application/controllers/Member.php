<?php

/***************************************************************************************************
 * Copyright (c) 2020. by Camwel Corporate Solution PVT LTD
 * This project is developed and maintained by Camwel Corporate Solution PVT LTD.
 * Nobody is permitted to modify the source or any part of the project without
 * permission. Project Developer: Camwel Corporate Solution PVT LTD Developed for: Exolim IT Services
 * Pvt Ltd
 **************************************************************************************************/

defined('BASEPATH') or exit('No direct script access allowed');

class Member extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if ($this->login->check_member() == false) {
            redirect(site_url('site/login'));
        }
        $this->load->model("Earning");
        $this->load->library('pagination');
        $this->load->library('cart');
    }

    public function index()
    {
        $details = $this->db_model->select_multi('total_a, total_b, total_c, total_d, total_e,A,B,level_completed', 'member', array('id' => $this->session->user_id));

        // ##added by ishu start ##
        // $last_donation_record = $this->db_model->select_multi('*', 'donation_record', array('userid' => $this->session->user_id,'type'=>'By Member'));
        // $last_donation_amount = $last_donation_record->amount;
        // //    echo $last_donation_amount."<br>";
        // $total_earning = $this->db_model->sum('amount', 'earning', array('userid' => $this->session->user_id));
        // if ($total_earning >= 4000 && $last_donation_amount == '') {
        //     $data['donation_link'] = "<p class='text-center'><a href=" . site_url('member/donate_now/') . " class='btn btn-success '> Donate Now</a><p>";
        //     $data['redonate'] = 'yes';
        //     // echo 1;
        // } elseif ($total_earning >= 15000  && $last_donation_amount == 500) {
        //     $data['donation_link'] = "<p class='text-center'><a href=" . site_url('member/donate_now/') . " class='btn btn-success '> Donate Now</a><p>";
        //     $data['redonate'] = 'yes';
        //     // echo 2;
        // } elseif ($total_earning >= 40000  && $last_donation_amount == 1000) {
        //     $data['donation_link'] = "<p class='text-center'><a href=" . site_url('member/donate_now/') . " class='btn btn-success '> Donate Now</a><p>";
        //     $data['redonate'] = 'yes';
        //     // echo 3;
        // }
        // elseif ($total_earning >= 100000  && $last_donation_amount == 2000) {
        //     $data['donation_link'] = "<p class='text-center'><a href=" . site_url('member/donate_now/') . " class='btn btn-success '> Donate Now</a><p>";
        //     $data['redonate'] = 'yes';
        //     // echo 3;
        // }
        //  else {
        //     $data['redonate'] = 'No';
        //     // echo 4;
        // }

        $elig = $this->Earning->check_donation_eligibility();
        $data['redonate']      = $elig['redonate'];
        $data['donation_link'] = $elig['donation_link'];

        // ##added by ishu start ##


        $this->ASarray = array();
        $downline_report = $this->active_subscriber($this->session->user_id);
        $data['downline'] = $downline_report['PersonCount'];
        $this->ASarray = array();
        $downline_report = $this->active_subscriber($details->A);
        $data['left_downline'] = ($details->A) ? $downline_report['PersonCount'] + 1 : 0;
        $this->ASarray = array();
        $downline_report = $this->active_subscriber($details->B);
        $data['right_downline'] = ($details->B) ? $downline_report['PersonCount'] + 1 : 0;

        $data['level'] = $this->db_model->select('income_name', 'level_wise_income', array('id' => $details->level_completed));
        $data['topup_with'] = $this->db_model->select('topup', 'member', array('id' => $this->session->user_id));
        $data['details'] = $details;
        $data['title'] = 'Dashboard';
        $data['breadcrumb'] = 'dashboard';
        $data['products'] = $this->db->get('product')->result_array();
        $data['epin']      = $this->db->select('epin,amount')->Where(array('issue_to' => $this->session->user_id, 'status' => 'Un-used'))->get('epin')->result_array();
        $this->load->view('member/base', $data);
    }

    function donate_now()
    {
        // ##added by ishu start ##
        // $last_donation_record = $this->db_model->select_multi('*', 'donation_record', array('userid' => $this->session->user_id,'type'=>'By Member'));
        // $last_donation_amount = $last_donation_record->amount;
        // $total_earning = $this->db_model->sum('amount', 'earning', array('userid' => $this->session->user_id));
        // $donation_amount = 0;

        // if ($total_earning >= 4000 && $last_donation_amount == '') {
        //     $donation_amount = 500;
        // } elseif ($total_earning >= 15000  && $last_donation_amount == 500) {
        //     $donation_amount = 1000;
        // } elseif ($total_earning >= 40000  && $last_donation_amount == 1000) {
        //     $donation_amount = 2000;
        // } elseif ($total_earning >= 100000  && $last_donation_amount == 2000) {
        //     $donation_amount = 4000;
        // } else {
        //     $donation_amount = 0;
        // }

        // echo "donation amount=" . $donation_amount;

        $elig = $this->Earning->check_donation_eligibility();
        $donation_amount = $elig['donation_amount'];

        $wallet_amount = $this->db_model->select('balance', 'wallet', array('userid' => $this->session->user_id));
        // echo "<br>wallet amount=" . $wallet_amount;
        // die;

        if ($wallet_amount < $donation_amount) {
            $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Wallet Amount Not sufficient Donation amount ' . config_item('currency') . $donation_amount . '</div>');
            redirect(site_url('member'));
        }

        $data = array(
            'userid' => $this->session->user_id,
            'amount' => $donation_amount,
            'type'  => 'By Member',
            'level'  => 0,
            'date'   => date('Y-m-d H:i:s')
        );
        $this->db->insert('donation_record', $data);

        $data = array(
            'balance'         => $wallet_amount - $donation_amount,
            'without_tax_bal' => $wallet_amount - $donation_amount
        );
        $this->db->where(array('userid' => $this->session->user_id))->update('wallet', $data);

        $sponsor = $this->db_model->select('sponsor', 'member', array('id' => $this->session->user_id));

        $this->load->model('earning');
        $status = $this->earning->donation_dist($this->session->user_id, $sponsor, $donation_amount, $need_topup = TRUE);


        if ($status) {
            //  =========wallet transection report start ==========
            $data = array(
                'userid' => $this->session->user_id,
                'amount' => $donation_amount,
                'type'   => "DR",
                'remark' => "Donated",
                'date'   => date('Y-m-d')
            );
            $this->db->insert('wallet_transection_report', $data);
            //  =========wallet transection report end ==========
            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Donated&nbsp;' . config_item('currency') . $donation_amount . ' Successfully</div>');
            redirect(site_url('member'));
        } else {
            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Something Went Wrong</div>');
            redirect(site_url('member'));
        }


        // ##added by ishu start ##
    }

    public function business()
    {
        $data['breadcrumb'] = 'Business Plan';
        $data['layout'] = 'business/business_plan.php';
        $this->load->view('member/base', $data);
    }

    public function logout()
    {
        $this->session->sess_destroy();
        $this->session->set_flashdata('site_flash', '<div class="alert alert-info">You have been logged out !</div>');
        redirect(site_url('site/login'));
    }

    // CORE MEMBER PARTS HERE NOW ############################################################ STARTS :

    public function used_epin()
    {
        $config['base_url'] = site_url('member/used_epin');
        /* $config['per_page'] = 50;
        $config['total_rows'] = $this->db_model->count_all('epin', array(
            'status' => 'Un-used',
            'issue_to' => $this->session->user_id,
        ));
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($config);
 */
        $this->db->select('id, epin, amount, used_by, used_time')->from('epin')->where('status', 'Used')
            ->where('issue_to', $this->session->user_id)/* ->limit($config['per_page'], $page) */;

        $data['epin'] = $this->db->get()->result_array();

        $data['title'] = 'Used e-PINs';
        $data['layout'] = 'epin/used.php';
        $this->load->view('member/base', $data);
    }

    public function unused_epin()
    {
        $config['base_url'] = site_url('member/unused_epin');
        /* $config['per_page'] = 50;
        $config['total_rows'] = $this->db_model->count_all('epin', array(
            'status' => 'Un-used',
            'issue_to' => $this->session->user_id,
        ));
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($config); */

        $this->db->select('id, epin, amount, issue_to, generate_time, generate_time')->from('epin')
            ->where('status', 'Un-used')->where('issue_to', $this->session->user_id)
            /* ->limit($config['per_page'], $page) */;

        $data['epin'] = $this->db->get()->result_array();

        $data['title'] = 'Unused e-PINs';
        $data['layout'] = 'epin/unused.php';
        $this->load->view('member/base', $data);
    }

    public function transfer_epin()
    {

        $this->form_validation->set_rules('amount', 'e-PIN Amount', 'trim|required');
        $this->form_validation->set_rules('to', 'To User ID', 'trim|required');
        $this->form_validation->set_rules('qty', 'Number of e-PINs', 'trim|required');
        if ($this->form_validation->run() == false) {
            $data['title'] = 'Transfer e-PIN';
            $data['layout'] = 'epin/transfer_epin.php';
            $this->load->view('member/base', $data);
        } else {
            $amount = $this->common_model->filter($this->input->post('amount'), 'float');
            $to = $this->common_model->filter($this->input->post('to'));
            $from = $this->session->user_id;
            $qty = $this->common_model->filter($this->input->post('qty'), 'number');

            $avl_qty = $this->db_model->count_all('epin', array(
                'issue_to' => $from,
                'amount' => $amount,
                'status' => 'Un-used',
            ));
            if ($avl_qty < $qty) {
                $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">The User ID have only ' . $avl_qty . ' Un-used epin of ' . config_item('currency') . ' ' . $amount . '.</div>');
                $data['title'] = 'Transfer e-PIN';
                $data['layout'] = 'epin/transfer_epin.php';
                $this->load->view('member/base', $data);
            } else {
                $this->db->where(array(
                    'issue_to' => $from,
                    'amount' => $amount,
                    'status' => 'Un-used',
                ));
                $vals = array(
                    'issue_to' => $to,
                    'transfer_by' => $from,
                    'transfer_time' => date('Y-m-d'),
                );
                $this->db->limit($qty);
                $this->db->update('epin', $vals);

                $this->session->set_flashdata('common_flash', '<div class="alert alert-success">' . $qty . ' e-PIN transferred from  ' . $this->input->post('to') . ' to ' . $this->input->post('from') . ' of ' . config_item('currency') . ' ' . $amount . '.</div>');
                redirect('member/transfer_epin');
            }
        }
    }

    // implemented by israel start
    function requestfor_epin()
    {
        $data['title'] = 'Request for e-PIN';
        $data['request'] = $this->db->select('*')->from('epin_request')->where('requested_by', $this->session->userdata('user_id'))->order_by('id', 'desc')->get()->result_array();
        $data['epin_value'] = $this->db->select('prod_price')->from('product')->get()->result();
        $data['layout'] = 'epin/request.php';
        $this->load->view('member/base', $data);
    }

    function requestedfor_epin()
    {

        $this->form_validation->set_rules('epin_qty', 'e-PIN Quantity', 'trim|required');
        $this->form_validation->set_rules('epin_type', 'e-PIN Type', 'trim|required');

        if ($this->form_validation->run() == true) {
            $dat = $this->upload_image('payment_bill', 'attach_doc');
            if ($dat['icon'] == 'error') {
                $this->session->set_flashdata("common_flash", "<div class='alert alert-danger'>" . $dat['text'] . "</div>");
                redirect('Member/requestfor_epin', 'refresh');
            } else {

                $val = $this->input->post();
                // $path = "hello";
                $data = array(
                    'requested_by'            => $this->session->userdata('user_id'),
                    'epin_type'                => $val['epin_type'],
                    'epin_qty'                => $val['epin_qty'],
                    'total_amount'            => $val['total_amount'],
                    'screenshot_document'     => $dat['text'],
                    'request_date'            => date('Y-m-d H:i:s'),
                );
                $a = $this->db->insert('epin_request', $data);

                if ($a) {
                    $this->session->set_flashdata("common_flash", "<div class='alert alert-success'>Request Generated successfully </div>");

                    redirect('Member/requestfor_epin', 'refresh');
                } else {
                    $this->session->set_flashdata("common_flash", "<div class='alert alert-danger'>Something Went wrong Please try after some time </div>");

                    redirect('Member/requestfor_epin', 'refresh');
                }
            }
        } else {
            $da = array(
                'epin_qty' => form_error('epin_qty'),
                'epin_type' => form_error('epin_type'),
            );

            $this->session->set_flashdata("common_flash", "<div class='alert alert-danger'>please Make Proper request  Formate</div>");


            redirect('Member/requestfor_epin', 'refresh');
        }
    }
    // impemented by israel end




    public function generate_epin()
    {
        $this->form_validation->set_rules('amount', 'e-PIN Amount', 'trim|required');
        $this->form_validation->set_rules('userid', 'Issue to ID', 'trim|required');
        $this->form_validation->set_rules('number', 'Number of e-PINs', 'trim|required|max_length[3]');
        if ($this->form_validation->run() == false) {

            $data['title'] = 'Generate e-PIN';
            $data['epin_value'] = $this->db->select('prod_price')->from('product')->get()->result();

            $data['layout'] = 'epin/generate.php';


            $this->load->view('member/base', $data);
        } else {
            $amount = $this->common_model->filter($this->input->post('amount'), 'float');
            $userid = $this->common_model->filter($this->input->post('userid'));
            $qty = $this->common_model->filter($this->input->post('number'), 'number');
            $total_amt = $amount * $qty;
            $get_user_balance = $this->db_model->select('balance', 'wallet', array('userid' => $this->session->user_id));

            if ($get_user_balance < $total_amt) {
                $this->session->set_flashdata("common_flash", "<div class='alert alert-danger'>You wallet donot have sufficient balance to generate $qty e-PIN. Your wallet need to have " . config_item('currency') . $total_amt . "</div>");
                redirect('member/generate-epin');
            }

            $data = array();
            for ($i = 0; $i < $qty; $i++) {
                $rand = mt_rand(10000000, 99999999);
                $epin = $this->db_model->select("id", "epin", array("epin" => $rand));
                if ($rand == $epin) {
                    $rand = $rand + 1;
                }
                $array = array(
                    'epin' => $rand,
                    'amount' => $amount,
                    'issue_to' => $userid,
                    'generated_by' => $this->session->user_id,
                    'generate_time' => date('Y-m-d'),
                );
                array_push($data, $array);
            }
            $this->db->insert_batch('epin', $data);

            $arra = array(
                'balance' => ($get_user_balance - $total_amt),
            );
            $this->db->where('userid', $this->session->user_id);
            $this->db->update('wallet', $arra);

            //  =========wallet transection report start ==========
            $data = array(
                'userid' => $this->session->user_id,
                'amount' => $total_amt,
                'type'   => "DR",
                'remark' => "e-Pin Generated",
                'date'   => date('Y-m-d')
            );
            $this->db->insert('wallet_transection_report', $data);
            //  =========wallet transection report end ==========
            $this->session->set_flashdata("common_flash", "<div class='alert alert-success'>$qty e-PIN created successfully.</div>");
            $this->common_model->mail($this->db_model->select('email', 'member', array('id' => $userid)), 'e-PIN Issued', 'Dear Sir, <br/> e-PIN of Qty ' . $qty . ', has been issued to your account from user id: ' . config_item('ID_EXT') . $this->session->user_id . ' on behalf of us.<br/><br/>---<br/>Regards,<br/>' . config_item('company_name'));
            redirect('member/unused-epin');
        }
    }

    public function view_earning()
    {
        $config['base_url'] = site_url('member/view_earning');
        /* $config['per_page'] = 100;
        $config['total_rows'] = $this->db_model->count_all('earning', array('userid' => $this->session->user_id));
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($config); */

        $this->db->select('id, userid, amount, type, ref_id, date, pair_match')->from('earning')
            ->where('userid', $this->session->user_id)/* ->limit($config['per_page'], $page) */;

        $data['earning'] = $this->db->get()->result_array();

        $data['title'] = 'Earnings';
        $data['layout'] = 'income/view_earning.php';
        $this->load->view('member/base', $data);
    }

    public function topup_wallet()
    {
        if (!isset($_POST['amount']) && !isset($_POST['epin'])) {
            $data['title'] = 'Fund My Wallet';
            $data['layout'] = 'wallet/topup-wallet.php';
            $this->load->view('member/base', $data);
        } else {

            $epin = trim($this->input->post('epin'));
            $amount = trim($this->input->post('amount'));

            if ($epin !== "") {
                $epin_value = $this->db_model->select('amount', 'epin', array(
                    'epin' => $epin,
                    'status' => 'Un-used',
                ));

                if ($epin_value <= 0) {
                    $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">The entered e-PIN is invalid or doesn\'t exist.</div>');
                    redirect(site_url('member/topup-wallet'));
                } else {
                    $wallet_balance = $this->db_model->select('balance', 'wallet', array('userid' => $this->session->user_id));
                    $this->db->where(array('userid' => $this->session->user_id));
                    $this->db->update('wallet', array('balance' => $wallet_balance + $epin_value));

                    $data = array(
                        'status' => 'Used',
                        'used_by' => $this->session->user_id,
                        'used_time' => date('Y-m-d'),
                    );

                    $this->db->where('epin', $epin);
                    $this->db->update('epin', $data);

                    $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Fund is added to your wallet.</div>');
                    redirect(site_url('member/topup-wallet'));
                }
            } else {

                $user_data = $this->db_model->select_multi('sponsor, address, email, phone', 'member', array('id' => $this->session->user_id));

                $this->session->set_userdata('_user_id_', $this->session->user_id);
                $this->session->set_userdata('_user_name_', $this->session->name);
                $this->session->set_userdata('_inv_id_', rand());
                $this->session->set_userdata('_sponsor_', $user_data->sponsor);
                $this->session->set_userdata('_address_', $user_data->address);
                $this->session->set_userdata('_email_', $user_data->email);
                $this->session->set_userdata('_phone_', $user_data->phone);
                $this->session->set_userdata('_product_', 'Add Wallet Fund');
                $this->session->set_userdata('_price_', $amount);
                $this->session->set_userdata('_type_', 'wallet');
                $this->session->set_userdata('_coin_', $this->input->post('coin_wallet'));
                $this->load->config('pg');
                if (config_item('enable_coinpayments') == "Yes") {
                    $this->load->library('coinpaymentsapi');
                    $this->coinpaymentsapi->Setup(config_item('private_key'), config_item('pub_key'));
                    $data['p_info'] = $this->coinpaymentsapi->GetCallbackAddress($this->input->post('coin_wallet'), site_url('gateway/coinpayment_success'));

                    $this->db->insert('pending_wallet', array(
                        'userid' => $this->session->user_id,
                        'balance' => $amount,
                        'txn_id' => $data['p_info']['result']['address'],
                    ));
                    $bata['address'] = $data['p_info']['result']['address'];
                    $bata['dest_tag'] = $data['p_info']['result']['dest_tag'];
                    $bata['title'] = 'Fund My Wallet';
                    $bata['layout'] = 'wallet/topup-wallet.php';
                    $this->load->view('member/base', $bata);
                } else {
                    redirect('gateway/registration_form');
                }
            }
        }
    }

    public function failed_fund()
    {
        $this->session->set_flashdata("common_flash", "<div class='alert alert-danger'>Your payment is not completed. So your fund was not added.</div>");
        redirect(site_url('member/topup-wallet'));
    }

    public function complete_add_fund()
    {
        $wallet_balance = $this->db_model->select('balance', 'wallet', array('userid' => $this->session->user_id));
        $this->db->where(array('userid' => $this->session->user_id));
        $this->db->update('wallet', array('balance' => $wallet_balance + $this->session->_price_));
        $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Fund is added to your wallet.</div>');
        redirect(site_url('member/topup-wallet'));
    }


    public function my_rewards()
    {
        $config['base_url'] = site_url('member/my_rewards');
        /* $config['per_page'] = 100;
        $config['total_rows'] = $this->db_model->count_all('rewards', array('userid' => $this->session->user_id));
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($config); */

        $this->db->select('id, reward_id, date, paid_date, tid')->from('rewards')
            ->where('userid', $this->session->user_id)/* ->limit($config['per_page'], $page) */;

        $data['rewards'] = $this->db->get()->result_array();

        $data['title'] = 'My Rewards';
        $data['layout'] = 'income/rewards.php';
        $this->load->view('member/base', $data);
    }

    public function search_earning()
    {
        $data['title'] = 'Search Income';
        $data['layout'] = 'income/search_income.php';
        $this->load->view('member/base', $data);
    }

    public function income_search()
    {
        $income_name = $this->input->post('income_name');
        $startdate = $this->input->post('startdate');
        $enddate = $this->input->post('enddate');

        $this->db->select('id, userid, amount, type, ref_id, date, pair_match')->from('earning');
        if ($income_name !== "All") {
            $this->db->where('type', $this->input->post('income_name'));
        }
        $this->db->where('userid', $this->session->user_id);
        if (trim($startdate) !== "") {
            $this->db->where('date >=', $startdate);
        }
        if (trim($enddate) !== "") {
            $this->db->where('date <=', $enddate);
        }

        $data['earning'] = $this->db->get()->result_array();
        $data['title'] = 'Search Results';
        $data['layout'] = 'income/view_earning.php';
        $this->load->view('member/base', $data);
    }

    public function settings()
    {
        $this->form_validation->set_rules('oldpass', 'Current Password', 'trim|required');
        $this->form_validation->set_rules('newpass', 'New Password', 'trim|required');
        $this->form_validation->set_rules('repass', 'Retype Password', 'trim|required|matches[newpass]');
        if ($this->form_validation->run() == false) {
            $data['title'] = 'Change Password';
            $data['layout'] = 'profile/acsetting.php';
            $this->load->view('member/base', $data);
        } else {

            $mypass = $this->db_model->select('password', 'member', array('id' => $this->session->user_id));

            if (password_verify($this->input->post('oldpass'), $mypass) == true) {

                $array = array(
                    'password' => password_hash($this->input->post('newpass'), PASSWORD_DEFAULT),
                    'show_password' => $this->input->post('newpass'),
                );


                $this->db->where('id', $this->session->user_id);
                $this->db->update('member', $array);
                $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Settings Saved Successfully.</div>');
                redirect('member/settings');
            } else {
                $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">The entered "Current Password" is wrong.</div>');
                redirect('member/settings');
            }
        }
    }

    public function proile()
    {
        $this->form_validation->set_rules('oldpass', 'Current Password', 'trim|required');
        $data['data'] = $this->db_model->select_multi('*', 'member_profile', array('userid' => $this->session->user_id));
        if ($this->form_validation->run() == false) {
            $data['my'] = $this->db_model->select_multi('phone, email', 'member', array('id' => $this->session->user_id));
            $data['title'] = 'My Profile';
            $data['layout'] = 'profile/profile.php';
            $this->load->view('member/base', $data);
        } else {

            $mypass = $this->db_model->select('password', 'member', array('id' => $this->session->user_id));

            if (password_verify($this->input->post('oldpass'), $mypass) == true) {
                $add_proof = '';
                $id_proof = '';
                if (trim($_FILES['id_proof']['name'] !== "")) {

                    $this->load->library('upload');

                    if (!$this->upload->do_upload('id_proof')) {
                        $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">ID Proof not uploaded..<br/>' . $this->upload->display_errors() . '</div>');
                        redirect('member/proile');
                    } else {
                        $image_data = $this->upload->data();
                        $id_proof = $image_data['file_name'];
                        unlink('uploads/' . $data['data']->id_proof);
                    }
                }

                if (trim($_FILES['add_proof']['name'] !== "")) {

                    $this->load->library('upload');

                    if (!$this->upload->do_upload('add_proof')) {
                        $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Address Proof not uploaded..<br/>' . $this->upload->display_errors() . '</div>');
                        redirect('member/proile');
                    } else {
                        $image_data = $this->upload->data();
                        $add_proof = $image_data['file_name'];
                        unlink('uploads/' . $data['data']->add_proof);
                    }
                }
                if (trim($_FILES['pass_book']['name'] !== "")) {

                    $this->load->library('upload');

                    if (!$this->upload->do_upload('pass_book')) {
                        $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Address Proof not uploaded..<br/>' . $this->upload->display_errors() . '</div>');
                        redirect('member/proile');
                    } else {
                        $image_data = $this->upload->data();
                        $pass_book = $image_data['file_name'];
                        unlink('uploads/' . $data['data']->pass_book);
                    }
                }

                $array = array(
                    'tax_no' => $this->input->post('tax_no'),
                    'aadhar_no' => $this->input->post('aadhar_no'),
                    'bank_ac_no' => $this->input->post('bank_ac_no'),
                    'bank_name' => $this->input->post('bank_name'),
                    'bank_ifsc' => $this->input->post('bank_ifsc'),
                    'bank_branch' => $this->input->post('bank_branch'),
                    'btc_address' => $this->input->post('btc_address'),
                    'nominee_name' => $this->input->post('nominee_name'),
                    'nominee_add' => $this->input->post('nominee_add'),
                    'nominee_relation' => $this->input->post('nominee_relation'),
                    'date_of_birth' => $this->input->post('date_of_birth'),
                    'gstin' => $this->input->post('gstin'),
                    'id_proof' => $id_proof,
                    'add_proof' => $add_proof,
                    'pass_book' => $pass_book
                );
                $this->db->where('userid', $this->session->user_id);
                $this->db->update('member_profile', $array);

                $array = array(
                    'name' => $this->input->post('my_name'),
                    'phone' => $this->input->post('my_phone'),
                    'email' => $this->input->post('my_email'),
                );
                $this->db->where('id', $this->session->user_id);
                $this->db->update('member', $array);

                $this->session->set_userdata('name', $this->input->post('my_name'));
                $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Profile Updated Successfully.</div>');
                redirect('member/proile');
            } else {
                $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">The entered "Current Password" is wrong.</div>');
                redirect('member/proile');
            }
        }
    }

    public function welcome_letter()
    {
        $data['file_data'] = file_get_contents(FCPATH . "uploads/welcome_letter.txt");
        $data['title'] = 'Welcome Letter';
        $data['layout'] = "profile/welcome_letter.php";
        $this->load->view('member/base', $data);
    }

    public function topup()
    {
        $epin_value = $this->db_model->select('amount', 'epin', array(
            'epin' => trim($this->input->post('topup')),
            'status' => 'Un-used',
        ));

        if ($epin_value > 0) {
            $data = array(
                'topup' => $epin_value,
            );
            $this->db->where('id', $this->session->user_id);
            $this->db->update('member', $data);

            $data = array(
                'status' => 'Used',
                'used_by' => $this->session->user_id,
                'used_time' => date('Y-m-d'),
            );
            $this->db->where('epin', trim($this->input->post('topup')));
            $this->db->update('epin', $data);
            ///topup Data Entry
            $data = array(
                'user_id' => $this->session->user_id,
                'epin' => trim($this->input->post('topup')),
                'topup_amount' => $epin_value,
                'topup_by' => $this->session->user_id,
                'date' => date('Y-m-d'),
            );
            $this->db->insert('topup_record', $data);

            $this->load->model('earning');
            if (config_item('fix_income') == "Yes" && $epin_value > 0 && config_item('give_income_on_topup') == "Yes") {
                $this->earning->fix_income($this->session->user_id, $this->db_model->select('sponsor', 'member', array('id' => $this->session->user_id)), $epin_value);
            } else if (config_item('fix_income') !== "Yes" && $epin_value > 0 && config_item('give_income_on_topup') == "Yes") {

                $this->earning->reg_earning(
                    $this->session->user_id,
                    $this->db_model->select('sponsor', 'member', array('id' => $this->session->user_id)),
                    $this->db_model->select('signup_package', 'member', array('id' => $this->session->user_id)),
                    false
                );
            }

            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Successfully Top-uped your account.</div>');
            redirect(site_url('member/update_legs'));
        } else {
            $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">The entered e-PIN is not valid or used.</div>');
            redirect(site_url('member/update_legs'));
        }
    }

    public function my_invoices()
    {
        $config['base_url'] = site_url('member/my_invoices');
        /* $config['per_page'] = 50;
        $config['total_rows'] = $this->db_model->count_all('invoice', array(
            'userid' => $this->session->fran_id,
            'user_type' => 'Franchisee',
        ));
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($config); */

        $this->db->from('invoice')->where(array(
            'userid' => $this->session->user_id,
            'user_type' => 'Member',
        ))->order_by('id', 'DESC')/* ->limit($config['per_page'], $page) */;
        $data['invoice'] = $this->db->get()->result();
        $data['title'] = 'My Invoices';
        $data['breadcrumb'] = 'My Invoices';
        $data['layout'] = 'invoice/my_invoices.php';
        $this->load->view('member/base', $data);
    }

    public function invoice_view($id)
    {
        $data['result'] = $this->db_model->select_multi('*', 'invoice', array('id' => $id));
        $this->load->view('member/invoice/print_invoice.php', $data);
    }
    public function direct_reward()
    {
        $id = $this->db->select('id')->get('member')->result_array();
        foreach ($id as $e) {
            $sponsor = $this->db_model->count_all('member', array('sponsor' => $e['id']));
            echo $e['id'] . '=' . $sponsor . '<br>';

            if ($sponsor >= 10) {
                $reward = "Jio Phone (Direct Joining Target Achive Reward)";
            } else if ($sponsor >= 20) {
                $reward = "Stan Fan (Direct Joining Target Achive Reward)";
            } else if ($sponsor >= 50) {
                $reward = "Mixture Grinder (Direct Joining Target Achive Reward)";
            } else if ($sponsor >= 100) {
                $reward = "Android Mobile (Direct Joining Target Achive Reward)";
            } else if ($sponsor >= 200) {
                $reward = "Two wheeler or 20000 cash (Direct Joining Target Achive Reward)";
            }
            $array = array(
                'reward_id' => $reward,
                'user_id'   => $e['id'],
                'date'      => date('y-m-d'),
                'status'    => 'pending',
            );
            $this->db->insert('rewards', $array);
        }
    }

    public function topup_member()
    {

        $this->form_validation->set_rules('userid', 'User ID', 'trim|required');
        $this->form_validation->set_rules('topup', 'Top Up Epin', 'trim|required');
        $this->form_validation->set_rules('product', 'Package', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $data['title']      = 'Re Donate';
            $data['breadcrumb'] = 'Re Donate';
            $data['layout']     = 'upgrade/topup.php';
            $current_package = $this->db_model->select('signup_package', 'member', array('id' => $this->session->user_id));
            $data['products']   = $this->db->where('id', $current_package + 1)->get('product')->result_array();
            $data['epin']      = $this->db->select('epin,amount')->Where(array('issue_to' => $this->session->user_id, 'status' => 'Un-used'))->get('epin')->result_array();
            $this->load->view('member/base', $data);
        } else {
            $epin_value = $this->db_model->select('amount', 'epin', array(
                'epin' => trim($this->input->post('topup')),
                'status' => 'Un-used',
            ));
            $product = $this->input->post('product');
            // changes by ishu start
            $pack = $this->db->select('id,prod_price')->where('id', $product)->from('product')->get()->row();
            if ($pack->prod_price != $epin_value) {
                $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Package Price and e-Pin value Not Matched. Must be Same</div>');
                redirect('Member/topup_member', 'refresh');
            }

            $sign = $this->db->select('signup_package,topup')->where('id', $this->input->post('userid'))->from('member')->get()->row();
            if ($sign->signup_package == $product) {
                $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Member Already Topup With same Package </div>');
                redirect('Member/topup_member', 'refresh');
            }

            // changes by ishu end
            // authenticate userid start
            $userid = $this->common_model->filter($this->input->post('userid'));
            $member_det = $this->db->select('id')->from('member')->where('id', $userid)->get()->row();
            $userid = $member_det->id;

            if ($userid == false) {
                $this->session->set_flashdata("common_flash", "<div class='alert alert-danger'>Userid Not valid Please Try again</div>");
                redirect('admin/generate_epin', 'refresh');
            }
            // authenticate userid end

            if ($epin_value > 0) {
                $data   = array(
                    'epin' => trim($this->input->post('topup')),
                    'topup' => $epin_value,
                    'signup_package' => $product
                );
                $this->db->where('id', $userid);
                $this->db->update('member', $data);

                $data = array(
                    'status'    => 'Used',
                    'used_by'   => $userid,
                    'used_time' => date('Y-m-d'),
                );
                $this->db->where('epin', trim($this->input->post('topup')));
                $this->db->update('epin', $data);
                ///Topup Data Entry
                $data = array(
                    'user_id'         => $this->input->post('userid'),
                    'product_id'      => $product,
                    'epin'            => trim($this->input->post('topup')),
                    'topup_amount'    => $epin_value,
                    'topup_by'        => $this->session->user_id,
                    'date'            => date('Y-m-d'),
                );
                $this->db->insert('topup_record', $data);

                $this->load->model('earning');
                if (config_item('fix_income') == "Yes" && $epin_value > 0 && config_item('give_income_on_topup') == "Yes") {
                    $this->earning->fix_income($userid, $this->db_model->select('sponsor', 'member', array('id' => $userid)), $epin_value);
                } else if (config_item('fix_income') !== "Yes" && $epin_value > 0 && config_item('give_income_on_topup') == "Yes") {
                    $this->earning->reg_earning($userid, $this->db_model->select('sponsor', 'member', array('id' => $userid)), $this->db_model->select('signup_package', 'member', array('id' => $userid)));
                }

                $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Successfully Top-uped User account.</div>');
                // redirect(site_url('member/update_legs'));
                redirect(site_url('member'));
            } else {
                $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">The entered e-PIN is not valid or used.</div>');
                // redirect(site_url('member/update_legs'));
                redirect(site_url('member'));
            }
        }
    }

    public function update_legs()
    {
        $this->db->select("id,A,B,C,D,E")->from("member"); ///->where("topup >", "0")
        $data = $this->db->get()->result();
        foreach ($data as $result) {
            if ($result->A !== "0") {
                $count_a = $this->count_node($result->A);
                //$total_a_pv = $this->count_pv($result->A) + $this->db_model->select("mypv", "member", array("id" => $result->A));
                $total_a_matching = $this->count_matching($result->A) + $this->db_model->select("my_business", "member", array("id" => $result->A));
                //$total_a_investment = $this->count_investment($result->A) + $this->db_model->select("topup", "member", array("id" => $result->A));
            } else {
                $count_a = 0;
                $total_a_pv = 0;
                $total_a_matching = 0;
                $total_a_investment = 0;
            }
            if ($result->B !== "0") {
                $count_b = $this->count_node($result->B);
                //$total_b_pv = $this->count_pv($result->B) + $this->db_model->select("mypv", "member", array("id" => $result->B));
                $total_b_matching = $this->count_matching($result->B) + $this->db_model->select("my_business", "member", array("id" => $result->B));
                //$total_b_investment = $this->count_investment($result->B) + $this->db_model->select("topup", "member", array("id" => $result->B));
            } else {
                $count_b = 0;
                $total_b_pv = 0;
                $total_b_matching = 0;
                $total_b_investment = 0;
            }
            if ($result->C !== "0") {
                $count_c = $this->count_node($result->C);
                //$total_c_pv = $this->count_pv($result->C) + $this->db_model->select("mypv", "member", array("id" => $result->C));
                $total_c_matching = $this->count_matching($result->C) + $this->db_model->select("my_business", "member", array("id" => $result->C));
            } else {
                $count_c = 0;
            }
            if ($result->D !== "0") {
                $count_d = $this->count_node($result->D);
                //$total_d_pv = $this->count_pv($result->D) + $this->db_model->select("mypv", "member", array("id" => $result->D));
                $total_d_matching = $this->count_matching($result->D) + $this->db_model->select("my_business", "member", array("id" => $result->D));
            } else {
                $count_d = 0;
            }
            if ($result->E !== "0") {
                $count_e = $this->count_node($result->E);
                //$total_e_pv = $this->count_pv($result->E) + $this->db_model->select("mypv", "member", array("id" => $result->E));
                $total_e_matching = $this->count_matching($result->E) + $this->db_model->select("my_business", "member", array("id" => $result->E));
            } else {
                $count_e = 0;
            }
            $data = array("total_a" => $count_a, "total_b" => $count_b, "total_c" => $count_c, "total_d" => $count_d, "total_e" => $count_e, "total_a_pv" => $total_a_pv, "total_b_pv" => $total_b_pv, "total_c_pv" => $total_c_pv, "total_d_pv" => $total_d_pv, "total_e_pv" => $total_e_pv, "total_a_matching_incm" => $total_a_matching, "total_b_matching_incm" => $total_b_matching, "total_c_matching_incm" => $total_c_matching, "total_d_matching_incm" => $total_d_matching, "total_e_matching_incm" => $total_e_matching, "total_a_investment" => $total_a_investment, "total_b_investment" => $total_b_investment);
            $this->db->where("id", $result->id);
            $this->db->update("member", $data);
        }
        $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Successfully Top-uped User account.</div>');
        redirect(site_url('member/binary_payout'));
    }

    public function count_node($id, $i = 0)
    {
        if ($i == 0) {
            $top_up = $this->db_model->select("topup", "member", array("id" => $id));
            if (0 < $top_up) {
                $i = $i + 1;
            }
        }
        $this->db->select("id,topup")->where("position", $id);
        $data = $this->db->get("member")->result();
        $countdata = $this->db_model->count_all("member", array("position" => $id, "topup >" => "0"));
        $i = $i + $countdata;
        foreach ($data as $result) {
            if ($result->id) {
                $i = $this->count_node($result->id, $i);
            }
        }
        return $i;
    }
    public function count_matching($id, $i = 0)
    {
        $this->db->select("id,my_business")->where("position", $id);
        $data = $this->db->get("member")->result();
        $countdata = $this->db_model->sum("my_business", "member", array("position" => $id, "my_business !=" => "0"));
        $i = $i + $countdata;
        foreach ($data as $result) {
            if ($result->id) {
                $i = $this->count_matching($result->id, $i);
            }
        }
        return $i;
    }

    public function topup_list()
    {
        $config['base_url'] = site_url('member/topup_list');
        /* $config['per_page'] = 100;
        $config['total_rows'] = $this->db_model->count_all('earning', array('userid' => $this->session->user_id));
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($config); */

        $this->db->select('*')->from('topup_record')->where('user_id', $this->session->user_id);
        /* ->limit($config['per_page'], $page) */

        $data['topup'] = $this->db->get()->result_array();

        $data['title'] = 'Topup List';
        $data['layout'] = 'upgrade/topup_list.php';
        $this->load->view('member/base', $data);
    }

    public function binary_payout()
    {
        $member = $this->db->select('id,A,B,status,topup')->get('member')->result_array();
        foreach ($member as $m) {
            //echo $m['id']." => A =>".$m['A']."=> B =>".$m['B'];
            $A = $this->db->select('topup,status')->where(array('id' => $m['A']))->get('member')->row_array();
            $B = $this->db->select('topup,status')->where(array('id' => $m['B']))->get('member')->row_array();
            //echo "Topup A =>".$A['topup']." Topup B =>".$B['topup']."<br>";
            if ($A['topup'] > 0 && $B['topup'] > 0 && $B['status'] == "Active" && $A['status'] == "Active" && $m['topup'] > 0 && $m['status'] == "Active" && !empty($A['topup']) && !empty($B['topup']) && !empty($B['status']) && !empty($A['status'])) {
                //echo "<pre>";
                //print_r($member);
                $count_product_binary = $this->db_model->count_all("product", array("matching_income >" => 0));
                $count_fix_binary = $this->db_model->select("binary_income", "fix_income", array("1 >" => 0));
                $count_invst_binary = $this->db_model->select("matching_income", "investment_pack", array(0));
                if (0 < $count_product_binary || 0 < $count_fix_binary || 0 < $count_invst_binary) {
                    $this->db->select("id,total_a,total_b,paid_a,paid_b,signup_package,mypv,total_a_matching_incm,total_b_matching_incm, total_c_matching_incm, paid_a_matching_incm, paid_b_matching_incm")->from("member")->where('status', "Active")->where("topup >", 0)->where("total_a >", 0)->where("total_b >", 0)->where("paid_a <", "total_a", false)->where("paid_b <", "total_b", false);
                    $data = $this->db->get()->result();
                    foreach ($data as $result) {
                        $this->load->model("earning");
                        $data2 = array(
                            "total_a" => $result->total_a,
                            "total_b" => $result->total_b,
                            "paid_a" => $result->paid_a,
                            "paid_b" => $result->paid_b,
                            "signup_package" => $result->signup_package,
                            //"mypv" => $result->mypv, 
                            //"total_a_matching_incm" => $result->total_a_matching_incm, 
                            //"total_b_matching_incm" => $result->total_b_matching_incm, 
                            //"total_c_matching_incm" => $result->total_c_matching_incm, 
                            "paid_a_matching_incm" => $result->paid_a_matching_incm,
                            "paid_b_matching_incm" => $result->paid_b_matching_incm
                        );
                        //echo $result->id."<br><pre>";
                        //print_r($data2);
                        $this->earning->process_binary($result->id, $data2);
                    }
                }
                redirect(site_url('tree/my-tree'));
            } else {
                redirect(site_url('tree/my-tree'));
            }
        }
    }

    function update_profile_pic()
    {
        $data['title'] = 'Update Image';
        $data['request'] = $this->db->select('*')->from('epin_request')->where('requested_by', $this->session->userdata('user_id'))->order_by('id', 'desc')->get()->result_array();
        $data['epin_value'] = $this->db->select('prod_price')->from('product')->get()->result();
        $data['layout'] = 'profile/profile_picture.php';
        $this->load->view('member/base', $data);
    }
    function update_image()
    {
        $mem_id = $this->input->post('mem_id');
        $da = $this->upload_image('member', 'image');

        if ($da['icon'] == 'success') {
            $img = $da['text'];
            $data = array(
                'my_img' => $img,
            );
            $memb = $this->db->select('my_img')->where('id', $mem_id)->get('member')->row();
            unlink($memb->my_img);
            $this->db->where('id', $mem_id)->update('member', $data);
            $data = array('text' => "<p style='padding:10px;background:green;color:white'>Successfully Updated Image!<p>", "icon" => "success");
        } else {
            $data = array('text' => "<p style='padding:10px;border:1px solid red;color:white'>" . $da['text'] . "</p>", "icon" => "error");
        }

        echo json_encode($data);
    }







    function id_card()
    {

        $user_id = $this->session->user_id;
        $data['user'] = $this->db->select('*')->from('member')->where('id', $user_id)->get()->row();
        $data['title'] = 'Id Card Preview';
        $data['layout'] = "profile/id_card.php";
        $this->load->view('member/base', $data);
    }

    function print_id_card()
    {
        $user_id = $this->session->user_id;
        $data['user'] = $this->db->select('*')->from('member')->where('id', $user_id)->get()->row();
        $this->load->view('member/profile/_print_id_card', $data);
    }



    function upload_image($path, $name)
    {
        $config['upload_path']          = './uploads/' . $path;
        $config['allowed_types']        = 'jpg|png|jpeg';
        // $config['max_size']             = 100;
        // $config['max_width']            = 1024;
        // $config['max_height']           = 768;

        $this->load->library('upload', $config);

        if ($this->upload->do_upload($name)) {
            $upload_data =  $this->upload->data();
            $image_path = "uploads/" . $path . '/' . $upload_data['file_name'];


            $a = array('photo' => $image_path);
            $this->session->set_userdata($a);


            $val = array('text' => $image_path, 'icon' => 'success');
        } else {
            $val = array('text' => $this->upload->display_errors(), 'icon' => 'error');
        }

        return $val;
    }

    function wallet_transection_report()
    {
        $data['member'] = $this->db->select('*')->from('wallet_transection_report')->where('userid', $this->session->user_id)->order_by('id', 'DESC')->get()->result();
        $data['title'] = 'Wallet Transection Report';
        $data['layout'] = "wallet/wallet_transactions_report.php";
        $this->load->view('member/base', $data);
    }

    // uuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuu
    private function active_subscriber($id, $i = 0)
    {
        // echo "hello";
        // die;
        $this->db->select("id,username,topup")->from("member")->where('position', (int)$id);
        $data = $this->db->get()->result();
        $getAllId = 'getAllId';
        foreach ($data as $dt) {
            $this->ASarray['PersonCount'] += 1;
            if (isset($this->ASarray[$getAllId])) {
                $this->ASarray[$getAllId] .= ',' . $dt->id;
            } else {
                $this->ASarray[$getAllId] = $dt->id;
            }
            $this->active_subscriber($dt->id, $i);
        }
        return $this->ASarray;
    }
    // uuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuu

    public function get_user_name()
    {
        $id = $this->input->post('id');
        echo $this->db_model->select('name', 'member', array('id' => $id));
    }

    public function transection_pin()
    {

        $this->form_validation->set_rules('transection_pin', 'Transection Pin', 'trim|required');
        $this->form_validation->set_rules('re_transection_pin', 'Re-Transection Pin', 'required|matches[transection_pin]');
        if ($this->form_validation->run() == false) {
            $data['tran_pin_have_or_not'] = $this->db_model->get_data('member', array('id' => $this->session->user_id), 'id,transection_pin');
            // $data['title'] = 'Create Transection Pin';
            $data['layout'] = 'transection_pin/create_transection_pin.php';
            $this->load->view('member/base', $data);
        } else {

            $uid     = $this->session->user_id;
            $show_password = $this->input->post('show_password');
            $transection_pin = $this->input->post('transection_pin');

            if ($this->db_model->get_data('member', array('id' => $uid, 'show_password' => $show_password), 'id')) {
                $array = array(
                    'transection_pin' => $transection_pin,
                );
                $this->db->where('id', $this->session->user_id);
                $this->db->update('member', $array);
                $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Transaction PIN generated successfully. Proceed with your transaction securely.</div>');
                redirect('member/transection_pin');
            } else {
                $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Your login password failed. Please try again or reset it.</div>');
                redirect('member/transection_pin');
            }
        }
    }



    public function re_transection_pin()
    {

        $this->form_validation->set_rules('transection_pin', 'Transection Pin', 'trim|required');
        $this->form_validation->set_rules('re_transection_pin', 'Re-Enter Transection Pin', 'required|matches[transection_pin]');
        if ($this->form_validation->run() == false) {
            // $data['title'] = 'Reset Transection Pin';
            $data['layout'] = 'transection_pin/reset_tran_pin.php';
            $this->load->view('member/base', $data);
        } else {

            $uid     = $this->session->user_id;
            // $show_password = $this->input->post('show_password');
            $transection_pin = $this->input->post('transection_pin');

            if ($this->db_model->get_data('member', array('id' => $uid,'transection_pin' => $this->input->post('old_transection_pin')), 'id,transection_pin')) {
                $array = array(
                    'transection_pin' => $transection_pin,
                );
                $this->db->where('id', $this->session->user_id);
                $this->db->update('member', $array);
                $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Transaction PIN Re-generated successfully. Proceed with your transaction securely.</div>');
                redirect('member/re_transection_pin');
            } else {
                $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Your Transaction Pin Not Matched. Please try again.</div>');
                redirect('member/re_transection_pin');
            }
        }
    }

}
