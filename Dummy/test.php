<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Scroll Down Example</title>

  <style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        }

        #main-screen {
        height: 100vh;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        background-color: #f0f0f0;
        }

        #second-section {
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #e0e0e0;
        }
  </style>
</head>
<body>
<div class="modal fade" id="pendingModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl pendingModalSize" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <!-- <button>Print</button>
                    <button id="close-pending-modal" data-bs-dismiss="modal">Close</button> -->
                    PATIENT REFERRAL INFORMATION
                </div>
                
                <div class="modal-body-incoming">
                    <div class="container">
                        <div class="left-div">
                            
                        </div>
                        <div class="right-div">

                            <div id="right-sub-div-a">
                                <div id="right-sub-div-a-1"> 
                                        <div class="right-sub-div"> <label>Case Number:</label><span id="case-no"> '. $response[0]['referral_id'].'</span> </div>
                                        <div class="right-sub-div"> <label>Age:</label><span id="pat-age"> '. $response[1]['pat_age'].'</span> </div>
                                </div>
                                <div id="right-sub-div-a-2"> <label>ICD-10 Diagnosis:</label><textarea class="form-control" id="pat-icddiag"> </textarea> </div>
                            </div>
                        
                            <div id="right-sub-div-b">
                                <div id="right-sub-div-b-1">
                                    <!-- <div class="right-sub-div"> 
                                        <label>Select Case Category:</label>
                                        <select class="form-control" id="select-response-status">
                                            <option value="">Select</option>
                                            <option value="Primary">Primary</option>
                                            <option value="Secondary">Secondary</option> 
                                            <option value="Tertiary">Tertiary</option>
                                        </select>
                                    </div> -->

                                    <div class="right-sub-div"> 
                                        <label>Select Response Status:</label>
                                        <select class="form-control" id="select-response-status">
                                            <option value="">Select</option>
                                            <option value="Approved">Approve</option>
                                            <option value="Deferred">Defer</option> 
                                            <option value="Interdepartamental">Interdepartamental Referral</option>
                                        </select>
                                    </div>

                                    <div class="right-sub-div"> <label>Process Date/Time:</label><span id="refer-agency"> '. $response[1]['pat_age'].'</span> </div>
                                    <div class="right-sub-div"> <label>Processed By:</label><span id="refer-agency"> asdf </span> </div>
                                </div>
                                <div id="right-sub-div-b-2"> <label>Deferred Reason:</label> <textarea class="form-control" id="defer-reason"></textarea> </div>
                            </div>
                            
                            <div id="right-sub-div-c">
                            </div>
                        </div>  
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" id="submit-modal-btn-incoming" class="btn btn-danger" data-bs-dismiss="modal">Submit</button>
                    <button type="button" id="close-modal-btn-incoming" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

  <script src="script.js"></script>
</body>

<script>
    document.getElementById('scroll-button').addEventListener('click', function() {
            document.getElementById('second-section').scrollIntoView({ behavior: 'smooth' });
    });
</script>
</html>