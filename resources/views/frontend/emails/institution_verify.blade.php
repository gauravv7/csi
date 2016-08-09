<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8"> <!-- utf-8 works for most cases -->
    <meta name="viewport" content="width=device-width"> <!-- Forcing initial-scale shouldn't be necessary -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> <!-- Use the latest (edge) version of IE rendering engine -->
    <title>Acceptance of Application for CSI Institutional Membership!</title> <!-- The title tag shows in email notifications, like Android 4.4. -->

    <!-- Web Font / @font-face : BEGIN -->
    <!-- NOTE: If web fonts are not required, lines 9 - 26 can be safely removed. -->
    
    <!-- Desktop Outlook chokes on web font references and defaults to Times New Roman, so we force a safe fallback font. -->
    <!--[if mso]>
        <style>
            * {
                font-family: sans-serif !important;
            }
        </style>
    <![endif]-->
    
    <!-- All other clients get the webfont reference; some will render the font and others will silently fail to the fallbacks. More on that here: http://stylecampaign.com/blog/2015/02/webfont-support-in-email/ -->
    <!--[if !mso]><!-->
        <!-- insert web font reference, eg: <link href='https://fonts.googleapis.com/css?family=Roboto:400,700' rel='stylesheet' type='text/css'> -->
    <!--<![endif]-->

    <!-- Web Font / @font-face : END -->
    
    <!-- CSS Reset -->
    <style type="text/css">

        /* What it does: Remove spaces around the email design added by some email clients. */
        /* Beware: It can remove the padding / margin and add a background color to the compose a reply window. */
        html,
        body {
            margin: 0 auto !important;
            padding: 0 !important;
            height: 100% !important;
            width: 100% !important;
        }
        
        /* What it does: Stops email clients resizing small text. */
        * {
            -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
        }
        
        /* What it does: Centers email on Android 4.4 */
        div[style*="margin: 16px 0"] {
            margin:0 !important;
        }
        
        /* What it does: Stops Outlook from adding extra spacing to tables. */
        table,
        td {
            mso-table-lspace: 0pt !important;
            mso-table-rspace: 0pt !important;
        }
                
        /* What it does: Fixes webkit padding issue. Fix for Yahoo mail table alignment bug. Applies table-layout to the first 2 tables then removes for anything nested deeper. */
        table {
            border-spacing: 0 !important;
            border-collapse: collapse !important;
            table-layout: fixed !important;
            Margin: 0 auto !important;
        }
        table table table {
            table-layout: auto; 
        }
        
        /* What it does: Uses a better rendering method when resizing images in IE. */
        img {
            -ms-interpolation-mode:bicubic;
        }
        
        /* What it does: Overrides styles added when Yahoo's auto-senses a link. */
        .yshortcuts a {
            border-bottom: none !important;
        }
        
        /* What it does: A work-around for iOS meddling in triggered links. */
        .mobile-link--footer a,
        a[x-apple-data-detectors] {
            color:inherit !important;
            text-decoration: underline !important;
        }
      
    </style>
    
    <!-- Progressive Enhancements -->
    <style>
        
        /* What it does: Hover styles for buttons */
        .button-td,
        .button-a {
            transition: all 100ms ease-in;
        }
        .button-td:hover,
        .button-a:hover {
            background: #555555 !important;
            border-color: #555555 !important;
        }

        /* Media Queries */
        @media screen and (max-width: 480px) {

            /* What it does: Forces elements to resize to the full width of their container. Useful for resizing images beyond their max-width. */
            .fluid,
            .fluid-centered {
                width: 100% !important;
                max-width: 100% !important;
                height: auto !important;
                Margin-left: auto !important;
                Margin-right: auto !important;
            }
            /* And center justify these ones. */
            .fluid-centered {
                Margin-left: auto !important;
                Margin-right: auto !important;
            }

            /* What it does: Forces table cells into full-width rows. */
            .stack-column,
            .stack-column-center {
                display: block !important;
                width: 100% !important;
                max-width: 100% !important;
                direction: ltr !important;
            }
            /* And center justify these ones. */
            .stack-column-center {
                text-align: center !important;
            }
        
            /* What it does: Generic utility class for centering. Useful for images, buttons, and nested tables. */
            .center-on-narrow {
                text-align: center !important;
                display: block !important;
                Margin-left: auto !important;
                Margin-right: auto !important;
                float: none !important;
            }
            table.center-on-narrow {
                display: inline-block !important;
            }
                
        }
    </style>

</head>
<body width="100%" bgcolor="#F4F4F4" style="Margin: 0;">
<table cellpadding="0" cellspacing="0" border="0" height="100%" width="100%" bgcolor="#F4F4F4" style="border-collapse:collapse;"><tr><td valign="top">
    <center style="width: 100%;">

        <!-- Visually Hidden Preheader Text : BEGIN -->
        <div style="display:none;font-size:1px;line-height:1px;max-height:0px;max-width:0px;opacity:0;overflow:hidden;mso-hide:all;font-family: sans-serif;">
            Acceptance of Application for CSI Institutional Membership!
        </div>
        <!-- Visually Hidden Preheader Text : END -->

        <!--    
            Set the email width. Defined in two places:
            1. max-width for all clients set with Outlook, allowing the email to squish on narrow but never go wider than 600px.
            2. MSO tags for Desktop Windows Outlook enforce a 600px width.
        -->
        <div style="max-width: 680px;">
            <!--[if (gte mso 9)|(IE)]>
            <table cellspacing="0" cellpadding="0" border="0" width="680" align="center">
            <tr>
            <td>
            <![endif]-->

            <!-- Email Header : BEGIN -->
            <table cellspacing="0" cellpadding="0" border="0" align="left" width="100%" style="max-width: 680px;">
                <tr>
                    <td style="padding: 1.2em 0; text-align: right; width: 200px">
                        <img src={{ $message->embed(public_path('img/csi-logo.png')) }} width="100" height="100" alt="alt_text" border="0">
                    </td>
                    <td style="padding: 0.8em; text-align: left; font-family: sans-serif; font-size: 28px; mso-height-rule: exactly; line-height: 1.2em; color: #555555;">
                        Computer Society of India
                    </td>
                </tr>
            </table>
            <!-- Email Header : END -->

            <!-- Email Header : BEGIN -->
            <table cellspacing="0" cellpadding="0" border="0" align="left" bgcolor="#ffffff" width="100%" style="max-width: 680px;">
                <tr>
                    <td style="padding: 20px 0; text-align: center; font-family: sans-serif; font-size: 28px; mso-height-rule: exactly; line-height: 1.1em; color: #000000;">
                        Acceptance of Application for CSI Institutional Membership!
                    </td>
                </tr>
            </table>
            <!-- Email Header : END -->
            
            <!-- Email Body : BEGIN -->
            <table cellspacing="0" cellpadding="0" border="0" align="center" bgcolor="#ffffff" width="100%" style="max-width: 680px;">

                <!-- 1 Column Text: BEGIN -->
                <tr>
                    <td>
                        <table cellspacing="0" cellpadding="0" border="0" width="90%">
                            <tr>
                                <td style="padding: 40px 20px 0; text-align: center; font-family: sans-serif; font-size: 14px; mso-height-rule: exactly; line-height: 20px; color: #555555; border-top: 1px solid #9c9c9c; border-bottom: 1px solid #9c9c9c">
                                    Dear {{$name}},
                                    <br><br>
                                    Congratulations for successfully joining the Computer Society of India
                                    <br/>
                                    Your application having <strong>AID: {{ $aid }}</strong>, for <strong>Institutional Membership</strong> of <strong>Computer Society of India (CSI)</strong> has been verified successfully and <strong>the payments has been accepted</strong>. Kindly note that the verification is based upon the information / document submitted by you in your application form. If the same is found incorrect, at any later stage, your membership will immediately be terminated, and you will be held liable for the same.
                                    <br><br>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 10px; text-align: center; font-family: sans-serif; font-size: 14px; mso-height-rule: exactly; line-height: 20px; color: #555555;">
                                    Please note the following credentials,regarding your CSI membership:-
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 0 20px 40px; text-align: justify; font-family: sans-serif; font-size: 14px; mso-height-rule: exactly; line-height: 20px; color: #555555;">
                                    <table cellspacing="0" cellpadding="0" border="0" width="90%">
                                        <tr>
                                            <td style="text-align: left; padding: 0 10px;">
                                                Membership Number:
                                            </td>
                                            <td style="text-align: left">
                                                {{ $cid }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: left; padding: 0 10px;">
                                                Your login ID
                                            </td>
                                            <td style="text-align: left">
                                                {{$email}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: left; padding: 0 10px;">
                                                Membership Type: 
                                            </td>
                                            <td style="text-align: left">
                                                {{ $membership_type }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: left; padding: 0 10px;">
                                                Membership Validity Period: 
                                            </td>
                                            <td style="text-align: left">
                                                {{ $period }}
                                            </td>
                                        </tr>
                                    </table>
                                    <br/>
                                    The above credentials have also been sent by SMS on your registered Mobile Number as well as by email on your Primary E-Mail ID: {{ $email }}. You are requested to kindly login at www.csi-india.org and immediately change your User ID and Password. You can do it by using Edit Profile facility after first login. You can use this Edit Profile facility to keep your information updated, from time to time, as many number of times, you desire so. Kindly remember your <strong>CSI Membership Number</strong>, as mentioned above, for future reference, and quote the same, while corresponding with us, for any query.
                                    <br><br>
                                    You can login at our web-portal and can now Add / Edit / Remove details of your Institutional Nominees, update the profile of your Institution, from time to time, and can also submit request for opening up of CSI Students’ Branch at your Institution.  After login, under Members Area, kindly refer and take advantage of the facilities and services available to CSI Members, like downloading your Membership ID Card, Membership Certificate, free access to our publications, submission of proposals, etc.
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <!-- 1 Column Text : BEGIN -->

            </table>
            <!-- Email Body : END -->
            <!-- Email Header : BEGIN -->
            <table cellspacing="0" cellpadding="0" border="0" align="center" bgcolor="#ffffff" width="100%" style="max-width: 680px;">
                <tr>
                    <td style="padding: 30px; text-align: center; font-family: sans-serif; font-size: 13px; mso-height-rule: exactly; line-height: 1.1em; color: #474747;">
                        Please feel free to write us at helpdesk@csi-india.org, in case you have any further queries and we will be pleased to help.
                        <br><br>
                        Thanking you for your kind interest for joining CSI.
                    </td>
                </tr>
            </table>
            <!-- Email Header : END -->


            <!-- Email Footer : BEGIN -->
            <table cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 680px;">
                <tr>
                    <td style="padding: 40px 10px;width: 100%;font-size: 12px; font-family: sans-serif; mso-height-rule: exactly; line-height:18px; text-align: center; color: #888888;">
                        With warm regards,
                        <br/>
                        (Hony. Secretary)
                        <br/>
                        Computer Society of India (CSI)
                        <br><br>
                        Corporate Office:<br><span class="mobile-link--footer">Samruddhi Venture Park, Unit No.3, 4th Floor, MIDC 
                        <br>
                        Andheri (E), Mumbai-400 093 (Maharashtra), INDIA 
                        <br>
                        Phone: +91-22-29261700 Fax : +91-22-28302133
                        <br><br>
                        Education Directorate:
                        <br>
                        National Headquarters, CIT Campus, IV Cross Road
                        <br>
                        Taramani, Chennai-600 113 (Tamil Nadu), INDIA                    
                        <br>
                        Phone: +91-44-2254 1102 / 03 / 2874
                        <br>
                        E-Mail ID: secretary@csi-india.org 
                        <br>
                        Visit us at: <a href="www.csi-india.org">www.csi-india.org</a>
                        </p>

                        </span>
                    </td>
                </tr>
            </table>
            <!-- Email Footer : END -->

            <!--[if (gte mso 9)|(IE)]>
            </td>
            </tr>
            </table>
            <![endif]-->
        </div>
    </center>
</td></tr></table>
</body>
</html>

