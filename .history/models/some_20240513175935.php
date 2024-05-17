<?php 
include('functions.php'); 
//include('../../links.php');
$navbg="has-background-white"; 
$navtxtcolor="has-text-black"; 
require $_SERVER['DOCUMENT_ROOT'] .'/hats/lggcheads.php';
?>

<link rel="stylesheet" type="text/css" href="/lwgraduatenetwork.com/lggc/css/style.css">
<link rel="stylesheet" type="text/css" href="/lwgraduatenetwork.com/lggc/css/mediascreen.css">
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>



<style>
    /* Adjust the width of the label to match the textarea */
.label-width {
    width: 100%;
    display: inline-block;
}

/* Ensure the textarea fills the width of its container */
.textarea-width {
    width: 100%;
}

.no-year .ui-datepicker-year {
    display: none;
  }

</style>

<div class='bgheightspn' id='programs'>
    
    <div class='blackoverlay has-text-white' >
        <div class='init-pad' data-aos="fade-in" data-aos-duration="1000">
            <div class='has-text-weight-bold is-size-2-desktop is-size-4-touch px-5'>Global Body of mentors Sign Up Form</div>
        </div>
    </div>
</div>

<div class='section mt-6 has-background-grey has-text-black' >
    <!-- <div class='py-1 lnht has-text-centered'>
        <p class='is-size-6 has-text-black'><b>REGISTRATION</b></p>
    </div> -->
    <div class='columns py-6 has-background-white is-vcentered is-centered' style="border:1px solid #000;">
        <div class='column is-6 has-text-black' data-aos="fade-in" data-aos-duration="1000">
            <!--<div class="column is-size-2 has-text-black has-text-centered"><h2>SPONSOR REGISTRATION FORM</h2></div>-->
            <?php if(isset($_SESSION['error'])){ ?>
            <div class="alert alert-danger">
                <?php echo $_SESSION['error']; ?>
            </div>

            <?php }unset($_SESSION['error']); ?>

            <?php if(isset($_SESSION['success'])){ ?>

            <div class="alert alert-success">
                <strong>Success! </strong> <?php echo $_SESSION['success']; ?>
            </div>

            <?php }unset($_SESSION['success']); ?>
            
           
            <form class="p-2" action="index.php" method="POST" enctype="multipart/form-data">
                
                <div class="column has-text-black has-text-centered " style="padding-top: 50px;"><h5>Contact Information</h5></div>
                
                <div class="form-group">
                    <div class="columns p-3">
                        <div class="column is-12-desktop is-12-touch p-1">
                            <label for="fileToUpload">Prefered photo <span class="req"> * </span></label>
                            <input type="file" name="fileToUpload" id="fileToUpload">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="columns p-3">
                        <div class="column is-12 p-1">
                            <label for="firstname">Full name <span class="req"> * </span></label>
                            <input type="text" name="fullname" placeholder="Full name">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="columns ">
                        <div class="column is-6-desktop is-12-touch p-3">
                            <label for="email">Email<span class="req"> * </span></label>
                            <input type="text" name="email" placeholder="Email address">
                        </div>
                        <div class="column is-6-desktop is-12-touch p-3">
                            <label for="phone">Phone Number <span class="req"> * </span></label>
                            <input type="text" name="phone" placeholder="phone number">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="columns p-3">
                         <div class="column is-6-desktop is-12-touch p-1">
                            <label for="kcid">KingsChat ID <span class="req"> * </span></label>
                            <input type="text" name="kcid" placeholder="KingsChat ID">
                        </div>
                    </div>
                </div>

                <div class="form-group columns p-3">
                    <div class="column is-12-desktop is-12-touch p-1">                            
                        <label for="zone">Zone<span class="req"> * </span></label><br>
                        <select class="select" name="zone" style="background-color: white; height: 40px;">
                                        <option value="CE Lagos Zone 1">CE Lagos Zone 1</option>
                                        <option value="CE Lagos Zone 2">CE Lagos Zone 2</option>
                                        <option value="CE Lagos Zone 3">CE Lagos Zone 3</option>
                                        <option value="CE Lagos Zone 4">CE Lagos Zone 4</option>
                                        <option value="CE Lagos Zone 5">CE Lagos Zone 5</option>
                                        <option value="CHRIST EMBASSY LAGOS VIRTUAL ZONE">CHRIST EMBASSY LAGOS VIRTUAL ZONE</option>
                                        <option value="CE USA  Region 1 Zone 1">CE USA  Region 1 Zone 1</option>
                                        <option value="CE USA  Region 1 Zone 2">CE USA  Region 1 Zone 2</option>
                                        <option value="CE USA  Region 2 Zone 1">CE USA  Region 2 Zone 1</option>
                                        <option value="CE USA  Region 2 Zone 2">CE USA  Region 2 Zone 2</option>
                                        <option value="Texas Zone 1">Texas Zone 1</option>
                                        <option value="Texas Zone 2">Texas Zone 2</option>
                                        <option value="CE UK Region - Zone 1">CE UK Zone 1 DSP </option>
                                        <option value="CE UK Region - Zone 2">CE UK Zone 2 DSP</option>
                                        <option value="CE UK Region - Zone 3">CE UK Zone 3 DSP</option>
                                        <option value="CE UK Region - Zone 4">CE UK Zone 4 DSP</option>
                                        <option value="CE UK Region - Zone 4">CE UK Zone 1 - Region 2</option>
                                        <option value="CE UK Region - Zone 4">CE UK Zone 3 - Region 2</option>
                                        <option value="CE UK Region - Zone 4">CE UK Zone 4 - Region 2</option>
                                        <option value="CE SA Zone 1">CE SA Zone 1</option>
                                        <option value="CE SA Zone 2">CE SA Zone 2</option>
                                        <option value="CE SA Zone 3">CE SA Zone 3</option>
                                        <option value="CE SA Zone 4">CE SA Zone 4</option>
                                        <option value="CE SA Zone 5">CE SA Zone 5</option>
                                        <option value="CE Accra Ghana Zone">CE Accra Ghana Zone</option>
                                        <option value="CE Onitsha Zone">CE Onitsha Zone</option>
                                        <option value="CE PH Zone 1">CE PH Zone 1</option>
                                        <option value="CE PH Zone 2">CE PH Zone 2</option>
                                        <option value="CE PH Zone 3">CE PH Zone 3</option>
                                        <option value="CE Abuja Zone">CE Abuja Zone</option>
                                        <option value="CE Benin Zone 1">CE Benin Zone 1</option>
                                        <option value="CE Mid-West Zone">CE Mid-West Zone</option>
                                        <option value="Kenya Zone">Kenya Zone</option>
                                        <option value="CE UK Region 2 - Zone 1">CE UK Region 2 - Zone 1</option>
                                        <option value="CE UK Region 2 - Zone 3">CE UK Region 2 - Zone 3</option>
                                        <option value="CE UK Region 2 - Zone 4">CE UK Region 2 - Zone 4</option>
                                        <option value="Western Europe Zone 1">Western Europe Zone 1</option>
                                        <option value="Western Europe Zone 2">Western Europe Zone 2</option>
                                        <option value="Western Europe Zone 3">Western Europe Zone 3</option>
                                        <option value="Western Europe Zone 4">Western Europe Zone 4</option>
                                        <option value="CE Quebec Zone">CE Quebec Zone</option>
                                        <option value="CE EWCA Zone 2">CE EWCA Zone 2</option>
                                        <option value="CE EWCA Zone 3">CE EWCA Zone 3</option>
                                        <option value="CE EWCA Zone 4">CE EWCA Zone 4</option>
                                        <option value="CE EWCA Zone 5">CE EWCA Zone 5</option>
                                        <option value="CE EWCA Zone 6">CE EWCA Zone 6</option>
                                        <option value="CE North West Zone 1">CE North West Zone 1</option>
                                        <option value="CE North West Zone 2">CE North West Zone 2</option>
                                        <option value="CE North Central Zone 1">CE North Central Zone 1</option>
                                        <option value="CE North Central Zone 2">CE North Central Zone 2</option>
                                        <option value="CE North East Zone 1">CE North East Zone 1</option>
                                        <option value="South East Zone 1">South East Zone 1</option>
                                        <option value="South East Zone 2">South East Zone 2</option>
                                        <option value="CE South South Zone 1">CE South South Zone 1</option>
                                        <option value="CE South South Zone 2">CE South South Zone 2</option>
                                        <option value="South West Zone 1">South West Zone 1</option>
                                        <option value="South West Zone 2">South West Zone 2</option>
                                        <option value="South West Zone 3">South West Zone 3</option>
                                        <option value="CE Aba Zone">CE Aba Zone</option>
                                        <option value="Edo North &amp; Edo Central Zone">Edo North &amp; Edo Central Zone</option>
                                        <option value="CE Benin Zone">CE Benin Zone</option>
                                        <option value="Ministry Center Abuja">Ministry Center Abuja</option>
                                        <option value="Ministry Centre Port Harcourt">Ministry Centre Port Harcourt</option>
                                        <option value="Ministry Centre Warri">Ministry Centre Warri</option>
                                        <option value="Ministry Centre Abeokuta">Ministry Centre Abeokuta</option>
                                        <option value="Ministry Center Calabar">Ministry Center Calabar</option>
                                        <option value="CE Canada Zone">CE Canada Zone</option>
                                        <option value="Corpers' Loveworld">Corpers' Loveworld</option>
                                        <option value="Others">Others</option>
                                        <option value="CE UK Region 2 - Zone 2">CE UK Region 2 - Zone 2</option>
                                        <option value="CE EWCA Zone 1">CE EWCA Zone 1</option>
                                        <option value="Middle East &amp; South East Asia">Middle East &amp; South East Asia</option>
                                        <option value="Eastern Europe Region">Eastern Europe Region</option>
                                        <option value="CE Lagos Zone 6">CE Lagos Zone 6</option>
                                        <option value="CE Lagos Sub Zone A">CE Lagos Sub Zone A</option>
                                        <option value="CE Lagos Sub Zone B">CE Lagos Sub Zone B</option>
                                        <option value="CE Lagos Sub Zone C">CE Lagos Sub Zone C</option>
                                        <option value="CE Uyo zone">CE Uyo zone</option>
                                        <option value="Warri  subzone">Warri  subzone</option>
                                        <option value="South East Zone 3">South East Zone 3</option>
                                        <option value="Ibadan Zone">Ibadan Zone</option>
                                        <option value="Loveworld UK Zone 4">Loveworld UK Zone 4</option>
                                        <option value="Toronto Zone">Toronto Zone</option>
                                        <option value="BLW Zone A">BLW Zone A</option>
                                        <option value="BLW Woji">BLW Woji</option>
                                        <option value="BLW Zone H">BLW Zone H</option>
                                        <option value="CE Chad">CE Chad</option>
                                        <option value="Ottawa Zone">Ottawa Zone</option>
                                        <option value="South Pacific">South Pacific</option>
                                    </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="columns ">
                        <div class="column is-6-desktop is-12-touch p-3">
                            <label for="pastor">Zonal Pastor<span class="req"> * </span></label>
                            <input type="text" name="pastor" placeholder="Zonal Pastor">
                        </div>
                        <div class="column is-6-desktop is-12-touch p-3">
                            <label for="church">Church/group <span class="req"> * </span></label>
                            <input type="text" name="church" placeholder="Church/group">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="columns ">
                        <div class="column is-6-desktop is-12-touch p-3">
                            <label for="country">Country <span class="req"> * </span></label>
                            <input type="text" name="country" placeholder="Country">
                        </div>
                        <div class="column is-6-desktop is-12-touch p-3">
                            <label for="birthday">Birthdate (D/M) <span class="req">*</span></label>
                            <input type="text" name="birthday" placeholder="birthday">
                        </div>
                    </div>
                </div>
                <div class="column has-text-black has-text-centered " style="padding-top: 50px;"><h5>Background Information</h5></div>

                <div class="form-group">
                    <div class="columns ">
                        <div class="column is-6-desktop is-12-touch p-3">
                            <label for="job">Current Job Title/Profession<span class="req"> * </span></label>
                            <input type="text" name="job" placeholder=" ">
                        </div>
                        <div class="column is-6-desktop is-12-touch p-3">
                            <label for="company">Company/Organization Name<span class="req">*</span></label>
                            <input type="text" name="company" placeholder=" ">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="columns ">
                        <div class="column is-6-desktop is-12-touch p-3">
                            <label for="industry">Industry/Field of Expertise<span class="req"> * </span></label>
                            <input type="text" name="industry" placeholder=" ">
                        </div>
                        <div class="column is-6-desktop is-12-touch p-3">
                            <label for="yoe">Years of Experience<span class="req">*</span></label>
                            <input type="text" name="yoe" placeholder=" ">
                        </div>
                    </div>
                </div>
                
                <div class="column has-text-black has-text-centered " style="padding-top: 50px;"><h5>Availability</h5></div>
                
                <div class="form-group">
                    <div class="columns p-3">
                        <div class="column is-12-desktop is-12-touch p-1">
                            <label for="time">Time Commitment <span style="font-weight: normal;">(e.g., hours per week or month available for mentoring)</span><span class="req"> * </span></label>
                            <input type="text" name="time" placeholder=" ">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="columns p-3">
                        <div class="column is-12-desktop is-12-touch p-1">
                            <label for="method">Preferred Method of Communication <span style="font-weight: normal;">(e.g., in-person, phone, email, video call meetings)</span><span class="req">*</span></label>
                            <input type="text" name="method" placeholder=" ">
                        </div>
                    </div>
                </div>
                <div class="column has-text-black has-text-centered " style="padding-top: 50px;"><h5> Mentoring Preferences</h5></div>
                
                 <div class="form-group">
                    <div class="columns p-3">
                        <div class="column is-12-desktop is-12-touch p-1">
                            <label for="type">Types of Mentoring Offered <span style="font-weight: normal;">(e.g., career advice, skill development, industry insights)</span><span class="req">*</span></label>
                            <input type="text" name="type" placeholder=" ">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="columns p-3">
                        <div class="column is-12-desktop is-12-touch p-1">
                            <label for="demogrphic">Preferred Mentee Demographics <span style="font-weight: normal;">(e.g., students, professionals in a specific field, early career individuals)</span><span class="req">*</span></label>
                            <input type="text" name="demographic" placeholder=" ">
                        </div>
                    </div>
                </div>
                <div class="column has-text-black has-text-centered " style="padding-top: 50px;"><h5> Experience and Skills</h5></div>
                
                <div class="form-group">
                    <div class="columns p-3">
                        <div class="column is-12-desktop is-12-touch p-1">
                            <label for="description">Brief Description of Previous Mentoring Experience (If any)</label>
                            <br>
                            <textarea name="description" rows="5" style="width: 100%;"></textarea>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="columns p-3">
                        <div class="column is-12-desktop is-12-touch p-1">
                            <label for="skill">Specific Skills or Areas of Expertise to Offer Guidance In<span class="req">*</span></label>
                            <br>
                            <textarea name="skill" rows="5" style="width: 100%;"></textarea>
                        </div>
                    </div>
                </div>
                <div class="column has-text-black has-text-centered " style="padding-top: 50px;"><h5> Motivation</h5></div>
                
                <div class="form-group">
                    <div class="columns p-3">
                        <div class="column is-12-desktop is-12-touch p-1">
                            <label for="reason">Reasons for Wanting to Be a Mentor<span class="req">*</span></label>
                            <br>
                            <textarea name="reason" rows="5" style="width: 100%;"></textarea>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="columns p-3">
                        <div class="column is-12-desktop is-12-touch p-1">
                            <label for="hope">What You Hope to Gain from Mentoring Others<span class="req">*</span></label>
                            <br>
                            <textarea name="hope" rows="5" style="width: 100%;"></textarea>
                        </div>
                    </div>
                </div>
                <div class="column has-text-black has-text-centered " style="padding-top: 50px;"><h5> Additional Information</h5></div>
                
                <div class="form-group">
                    <div class="columns p-3">
                        <div class="column is-12-desktop is-12-touch p-1">
                            <label for="other">Any Other Relevant Information or Comments<span class="req">*</span></label>
                            <br>
                            <textarea name="other" rows="5" style="width: 100%;"></textarea>
                        </div>
                    </div>
                </div>





                <div class="columns p-3">
                    <div class="column is-4-desktop is-12-touch text-center p-1">
                        <button type="submit" name="submitbtn" class="button purplebtn has-text-white mt-2">Submit</button>
                    </div>
                    <div class="column is-3-desktop is-12-touch text-center p-1">
                        <!-- <div class="form-redirect text-center is-size-7">Already Registered?, <span><a href="/lwgraduatenetwork.com/lggc/login"><b> LOGIN </b></a></span></div> -->
                        <!-- <div class="login-redirect text-center">Already Registered?, <span><a href="https://lwgraduatenetwork.com/lggc/login"><b> LOGIN </b></a></span></div> -->
                    </div>
                    <!-- <div class="column is-4-desktop is-12-touch text-center p-1">
                        <div class="sp">
                            <a class="" href="/lwgraduatenetwork.com/lggc/partner">
                                <div class="button purplebtn has-text-white mt-2">Sponsor</div>
                            </a>
                        <!-- <a href="https://lwgraduatenetwork.com/lggc/partner">
                                <div class="spn mb-3 mt-3">Become a sponsor</div>
                            </a> 
                        </div>
                    </div> -->
                </div>

                <!-- <div class="has-text-centered p-3">
                    <div class="form-redirect text-center is-size-7">Already Registered?, <span><a href="/lwgraduatenetwork.com/lggc2/login"><b> LOGIN </b></a></span></div>
                    <!-- <div class="login-redirect text-center">Already Registered?, <span><a href="https://lwgraduatenetwork.com/lggc/login"><b> LOGIN </b></a></span></div>
                </div> -->
            </form>

            

        </div>


        
    </div>
    
</div>

<script>
  $(function() {
    $("input[name='birthday']").datepicker({
      dateFormat: 'dd/mm', // Set the date format to display only day and month
      changeMonth: true, // Allow changing the month
     
      beforeShow: function(input, inst) {
        inst.dpDiv.addClass('no-year');
      }
    });
  });
</script>



<?php
$display="is-hidden";
?>

<?php require $_SERVER['DOCUMENT_ROOT'] .'/hats/tails.php'; ?>