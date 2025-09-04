<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>Help and Support</title>
    <style>
        .card {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            margin-bottom: 15px; /* Add spacing between cards */
        }
        .card-header {
            background-color: #007bff; /* Card header color */
            color: white; /* Text color */
            cursor: pointer; /* Pointer cursor */
        }
        .logo {
            width: 100px; /* Set logo width */
            height: auto; /* Maintain aspect ratio */
        }
        .contact-info {
            margin: 20px 0;
        }
        body {
            padding: 15px; /* Padding for mobile view */
        }
    </style>
</head>
<body class="bg-dark">

<div class="container">
    <div class="text-center mb-4">
       @if($companyLogo && $companyLogo->logo)
            <img src="{{ asset('storage/' . $companyLogo->logo) }}" alt="Company Logo" class="w-50">
        @else
            <img src="{{ asset('dashboard/img/icon.png') }}" alt="" class="w-50">
        @endif
        <h3 class="mt-2 text-light">Insurance Company</h3>
        <div class="contact-info">
            <p class="text-light">Email: {{$companyLogo->email}}</p>
            <p class="text-light">Contact: {{$companyLogo->phone}}</p>
        </div>
    </div>

    <div class="accordion" id="supportAccordion">
        
        <div class="card">
            <div class="card-header" id="headingTwo" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                <h5 class="mb-0">Customer Support</h5>
            </div>
            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#supportAccordion">
                <div class="card-body">
                    We are committed to providing you with the best possible service and assistance.

                    <h6>Contacting Customer Support</h6>
                    If you have any questions or need assistance while using our app, there are several ways to reach our customer support team:
                    <ol>
                        <li>
                            <strong>In-App Support</strong>
                            <ul>
                                <li><strong>Help Center:</strong> Access our comprehensive Help Center directly within the app. Here, you can find answers to frequently asked questions and detailed guides on how to navigate the app and manage your policies.</li>
                                <li><strong>Live Chat:</strong> For immediate assistance, use the live chat feature available in the app. Our customer support representatives are ready to help you in real time.</li>
                            </ul>
                        </li>
                        <li>
                            <strong>Email Support</strong>
                            <p>If you prefer to reach out via email, you can contact us at <a href="mailto:{{$companyLogo->email}}">{{$companyLogo->email}}</a>. Please include the following information in your message to help us assist you better:</p>
                            <ul>
                                <li>Your name</li>
                                <li>Policy number</li>
                                <li>A brief description of your issue or question</li>
                            </ul>
                        </li>
                        <li>
                            <strong>Phone Support</strong>
                            <p>For more personalized assistance, you can call our customer support hotline at {{$companyLogo->phone}}. Our representatives are available [insert hours of operation] to help you with any inquiries or concerns.</p>
                        </li>
                        <li>
                            <strong>FAQs</strong>
                            <p>Before reaching out, you may want to check our Frequently Asked Questions section, which covers common topics such as:</p>
                            <ul>
                                <li>How to file a claim</li>
                                <li>Updating your personal information</li>
                                <li>Making payments</li>
                                <li>Navigating policy details</li>
                            </ul>
                        </li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header" id="headingThree" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                <h5 class="mb-0">App Features and Navigation</h5>
            </div>
            <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#supportAccordion">
                <div class="card-body">
                    Our app is designed to help you manage your insurance needs conveniently. Here’s a quick overview of its key features and navigation:

                    <h6>Key Features:</h6>
                    <ul>
                        <li>User-Friendly Dashboard: Quickly view your policies, recent activities, and important notifications.</li>
                        <li>Policy Management: Access and update your insurance policies and personal information.</li>
                        <li>Claims Filing and Tracking: Easily file claims and track their status in real time.</li>
                        <li>Secure Payments: Make premium payments and set up auto-pay for hassle-free management.</li>
                        <li>Document Storage: Store and access important documents securely within the app.</li>
                        <li>Customer Support: Access the Help Center, live chat, and contact options for assistance.</li>
                        <li>Notifications and Alerts: Receive timely updates for policy renewals and payment reminders.</li>
                    </ul>

                    <h6>Navigation Guide:</h6>
                    <ol>
                        <li>Download and Install: Get the app from the App Store or Google Play. Create an account or log in.</li>
                        <li>Bottom Navigation Bar: Navigate easily through Home, Policies, Claims, Payments, and Support.</li>
                        <li>Search Functionality: Quickly find specific features or information.</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header" id="headingFour" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                <h5 class="mb-0">Security and Privacy</h5>
            </div>
            <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#supportAccordion">
                <div class="card-body">
                    <h6>Security Measures:</h6>
                    <ul>
                        <li>Data Encryption: All sensitive data, including personal information and payment details, is encrypted to prevent unauthorized access.</li>
                        <li>Secure Access Protocols: We use secure authentication methods, including two-factor authentication, to safeguard your account.</li>
                        <li>Regular Security Audits: Our systems undergo regular security assessments to identify and address vulnerabilities.</li>
                        <li>Firewalls and Monitoring: Advanced firewalls and monitoring systems are in place to detect and prevent potential threats.</li>
                    </ul>

                    <h6>Privacy Protection:</h6>
                    <ul>
                        <li>Data Collection: We only collect information necessary to provide our services. Your personal data will never be sold or shared without your consent.</li>
                        <li>User Control: You have control over your personal information, including the ability to access, update, or delete your data.</li>
                        <li>Compliance with Regulations: We adhere to applicable privacy laws and regulations to protect your rights and ensure the responsible handling of your data.</li>
                    </ul>
                    If you have any questions or concerns about our security and privacy practices, please contact us at <a href="mailto:{{$companyLogo->email}}"></a>. Your trust is important to us, and we are committed to protecting your information.
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header" id="headingFive" data-toggle="collapse" data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                <h5 class="mb-0">Technical Support</h5>
            </div>
            <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#supportAccordion">
                <div class="card-body">
                    <h6>What We Can Help With:</h6>
                    <ul>
                        <li>Troubleshooting: If you're facing difficulties with features or navigation, we can guide you through the necessary steps to resolve these issues.</li>
                        <li>Error Resolution: Our team is equipped to address any errors you may encounter, whether during account access, payments, or other functionalities.</li>
                        <li>User Experience Enhancement: We aim to make your experience as smooth as possible and can provide tips and solutions tailored to your needs.</li>
                    </ul>
                    <h6>How to Reach Us:</h6>
                    For quick assistance, please contact us through our various support channels:
                    <ul>
                        <li><strong>In-App Support:</strong> Use the live chat feature or visit the Help Center directly within the app for immediate guidance.</li>
                        <li><strong>Email:</strong> Reach out to us at <a href="mailto:{{$companyLogo->email}}">{{$companyLogo->email}}</a> with details about your issue, and we’ll respond promptly.</li>
                        <li><strong>Phone Support:</strong> Call our technical support hotline at [phone number].</li>
                    </ul>
                    Your satisfaction is our priority, and we are committed to resolving any issues you may face. Don’t hesitate to get in touch—your smooth experience with our services is our goal!
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header" id="headingSix" data-toggle="collapse" data-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                <h5 class="mb-0">Feedback and Suggestions</h5>
            </div>
            <div id="collapseSix" class="collapse" aria-labelledby="headingSix" data-parent="#supportAccordion">
                <div class="card-body">
                    Your feedback is vital to improving our services. We appreciate your insights on how we can enhance your experience.
                    You can provide feedback through the in-app feedback form, which allows you to share your thoughts directly. Alternatively, you can email us at <a href="mailto:{{$companyLogo->email}}">{{$companyLogo->email}}</a> with details about your feedback, including your name and specific suggestions. We may also send out occasional surveys to gather your opinions.
                    We welcome your feedback on app performance, user experience, new features or improvements, and customer support interactions. Your input is essential for our continuous improvement. Thank you for helping us serve you better!
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
