@extends('frontend.master')
@section('title', 'Registeration-Successful')
@section('custom-styles')
    <style>
        .section-header-style {
            margin-bottom: 50px;
            text-transform: capitalize;
            text-shadow: 0 0 1px #082238;
            font-size: 28px;
            /* text-align: center; */
            font-style: italic;
            color: #3f5586;
            /* text-decoration: underline; */
        }
        p {
            font-size: 16px;
            line-height: 1.4em;
        }
    </style>
@endsection
@section('main')
    <section id="main">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    @if($isPayableBalanced)
                        <h3 class="section-header-style">Successful submission of Application for CSI Nominee membership!</h3>
                    @else
                        <h3 class="section-header-style">Your Payments are not complete!</h3>
                    @endif
                    <div class="row">
                        <div class="col-md-12" style="border-left: 2px solid #3f5586;">
                            <p style="    font-style: italic;    font-size: 18px;    text-shadow: 0 0 1px #8A8A8A;">Dear {{$name}},</p>
                            <p>Your application for <strong>Nominee Membership</strong> of <strong>Computer Society of India (CSI)</strong> has been received successfully.</p>
                            <p>Your Associating Institution for <strong>CSI Nominee Membership</strong> is <strong>{{ $prof_member->institution->getName() }}</strong> .</p>
                            <p>
                                Your <strong>Application ID (AID) is {{$aid}}</strong>, which has also been sent by SMS at your registered Mobile Number as well as by email on your <strong>Primary E-Mail ID: {{$email}}</strong>. Kindly remember the same for future reference, and quote the same, while corresponding with us, regarding this request, till the same is verified and approved by us.</p>
                            <p>
                                Please note that we shall be verifying the credentials provided by you in your application form including the payment details and upon successful verification of the same, your membership request would be approved. Such
                                requests are normally resolved within a period of two weeks. If you do not hear anything from us, within two
                                weeks, kindly contact us at <strong>helpdesk@csi-india.org</strong>, giving your <strong>AID</strong> to enable us to track the status of your request and reply you back accordingly.</p>
                            <p>
                                After approval of your request, you will receive a <strong>Confirmation E-Mail</strong> as well as an <strong>SMS</strong> on your registered <strong>Mobile Number</strong> and above mentioned <strong>Primary E-Mail ID</strong>, which will have the details like your <strong>CSI Membership No., User
                                    ID</strong> and the <strong>Password</strong>, using which you will be able to login to the web-portal <strong>www.csi-india.org</strong> and avail
                                membership services, including updating your profile, from time to time.</p>
                            <p>
                                Please feel free to write us at helpdesk@csi-india.org, in case you have any further queries and we will be pleased to help.</p>
                            <br/>
                            <p>
                                Thanking you for your kind interest for joining CSI.
                            </p>
                            <p>With warm regards,</p>
                            <p>-<br/>
                                <strong>(Hony. Secretary)</strong>
                                <br/>
                                Computer Society of India (CSI)
                                <br/>
                                <strong>Corporate Office:</strong>
                                <br/>
                                Samruddhi Venture Park, Unit No.3, 4th Floor, MIDC
                                <br/>
                                Andheri (E), Mumbai-400 093 (Maharashtra), INDIA
                                <br/>
                                Phone: +91-22-29261700 Fax : +91-22-28302133
                                <br/>
                                <strong>
                                    Education Directorate:</strong>
                                <br/>
                                National Headquarters, CIT Campus, IV Cross Road
                                <br/>
                                Taramani, Chennai-600 113 (Tamil Nadu), INDIA
                                <br/>
                                Phone: +91-44-2254 1102 / 03 / 2874
                                <br/>
                                E-Mail ID: secretary@csi-india.org
                                <br/>
                                Visit us at: www.csi-india.org
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br/>
        <br/>
        <br/>

    </section>
@endsection