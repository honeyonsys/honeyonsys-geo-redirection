<?php
/*
Plugin Name: Honeyonsys Geo Redirection
Plugin URI: 
Description: Redirect the website by detecting the user nation/country. Admin can set the url for the specific countries list mention in the admin area of the plugin settings. Plugin is based on geoplugin
Version: 1.0 
Author: honeyonsys
Author URI: http://honeyonsys.github.io
License: GPL2
*/ 

$siteurl = get_option('siteurl');
define('HGR_FOLDER', dirname(plugin_basename(__FILE__)));
define('HGR_URL', $siteurl.'/wp-content/plugins/' . HGR_FOLDER);
define('HGR_FILE_PATH', dirname(__FILE__));
define('HGR_DIR_NAME', basename(HGR_FILE_PATH));

global $wpdb;

add_action('wp','front_init'); // initialization on the front site
function front_init(){
    $request = file_get_contents('http://ip-api.com/json/'.$_SERVER['REMOTE_ADDR']);
    $json_request = json_decode($request);        
    $args = array('post_type'   => 'hgr');
    $hgr_posts = get_posts( $args );
    
    foreach($hgr_posts as $post){
           $post_meta_loc = get_post_meta($post->ID,'hgr_location');
           $post_meta_redirect = get_post_meta($post->ID,'hgr_redirection');
           
           if($json_request->countryCode == $post_meta_loc[0]){       
               wp_redirect( $post_meta_redirect[0] );
               exit();    
           } 
        }
}


/*Loading admin styles*/
// add_action( 'admin_enqueue_scripts', 'load_hgr_admin_style' );
// function load_hgr_admin_style() {
// 	wp_enqueue_style( 'admin_css', HGR_URL.'/css/hgr.css', false, '1.0.0' );
// }

/*action for the admin form submission*/
add_action('admin_init', 'form_action');
function form_action(){
   
    if(isset($_POST['hgr_add_loc'])){
        $country_code = $_POST['country-code'];
        $redirect = $_POST['redirect-to'];

        $user_id = get_current_user_id();
        $attr = array(
            'post_author' => $user_id,
            'post_content' => $redirect,
            'post_title' => $country_code,
            'post_status' => 'publish',
            'post_type' => 'hgr',
            'post_parent' => 0,
            'menu_order' => 0,
            'guid' => '',
            'import_id' => 0,
            'context' => '',
        );
        $hgr_post_id = wp_insert_post($attr);
        add_post_meta($hgr_post_id, 'hgr_location', $country_code);
        add_post_meta($hgr_post_id, 'hgr_redirection', $redirect);
        wp_redirect(admin_url('admin.php?page=hgr-top-level-handle&amp;action=add'));
        
    }


    if(isset($_REQUEST['delete'])){
        $postid = $_REQUEST['delete'];
        wp_delete_post($postid);
        wp_redirect( admin_url('admin.php?page=hgr-top-level-handle') );
    }       
}


/*Adding admin menu*/
add_action('admin_menu', 'hgr_menu');
function hgr_menu(){
	add_menu_page('Honeyonsys Geo Redirect', 'HGR Settings', 'manage_options', 'hgr-top-level-handle', 'hgr_admin_form',HGR_URL.'/img/world_icon.gif','6');
}

function hgr_admin_form(){

?>
<div class="wrap">
<h3 class="title">Geo Redirection Settings</h3>
<form method="post" enctype="multipart/form-data"> 
  <p>Select country from the drop down and add redirection URL for that location. The user will redirect to that url if the site is visited from that country/zone.</p>
  <table style="padding-left:55px;" class="form-table permalink-structure" cellspacing="5">
  	<tr>
        
  		<td>
          <select name="country-code">
            <option value="DZ" >Algeria (DZ)</option>
            <option value="AD" >Andorra (AD)</option>
            <option value="AO" >Angola (AO)</option>
            <option value="AI" >Anguilla (AI)</option>
            <option value="AG" >Antigua &amp; Barbuda (AG)</option>
            <option value="AR" >Argentina (AR)</option>
            <option value="AM" >Armenia (AM)</option>
            <option value="AW" >Aruba (AW)</option>
            <option value="AU" >Australia (AU)</option>
            <option value="AT" >Austria (AT)</option>
            <option value="AZ" >Azerbaijan (AZ)</option>
            <option value="BS" >Bahamas (BS)</option>
            <option value="BH" >Bahrain (BH)</option>
            <option value="BD" >Bangladesh (BD)</option>
            <option value="BB" >Barbados (BB)</option>
            <option value="BY" >Belarus (BY)</option>
            <option value="BE" >Belgium (BE)</option>
            <option value="BZ" >Belize (BZ)</option>
            <option value="BJ" >Benin (BJ)</option>
            <option value="BM" >Bermuda (BM)</option>
            <option value="BT" >Bhutan (BT)</option>
            <option value="BO" >Bolivia (BO)</option>
            <option value="BA" >Bosnia Herzegovina (BA)</option>
            <option value="BW" >Botswana (BW)</option>
            <option value="BR" >Brazil (BR)</option>
            <option value="BN" >Brunei (BN)</option>
            <option value="BG" >Bulgaria (BG)</option>
            <option value="BF" >Burkina Faso (BF)</option>
            <option value="BI" >Burundi (BI)</option>
            <option value="KH" >Cambodia (KH)</option>
            <option value="CM" >Cameroon (CM)</option>
            <option value="CA" >Canada (CA)</option>
            <option value="CV" >Cape Verde Islands (CV)</option>
            <option value="KY" >Cayman Islands (KY)</option>
            <option value="CF" >Central African Republic (CF)</option>
            <option value="CL" >Chile (CL)</option>
            <option value="CN" >China (CN)</option>
            <option value="CO" >Colombia (CO)</option>
            <option value="KM" >Comoros (KM)</option>
            <option value="CG" >Congo (CG)</option>
            <option value="CK" >Cook Islands (CK)</option>
            <option value="CR" >Costa Rica (CR)</option>
            <option value="HR" >Croatia (HR)</option>
            <option value="CU" >Cuba (CU)</option>
            <option value="CY" >Cyprus North (CY)</option>
            <option value="CY" >Cyprus South (CY)</option>
            <option value="CZ" >Czech Republic (CZ)</option>
            <option value="DK" >Denmark (DK)</option>
            <option value="DJ" >Djibouti (DJ)</option>
            <option value="DM" >Dominica (DM)</option>
            <option value="DO" >Dominican Republic (DO)</option>
            <option value="EC" >Ecuador (EC)</option>
            <option value="EG" >Egypt (EG)</option>
            <option value="SV" >El Salvador (SV)</option>
            <option value="GQ" >Equatorial Guinea (GQ)</option>
            <option value="ER" >Eritrea (ER)</option>
            <option value="EE" >Estonia (EE)</option>
            <option value="ET" >Ethiopia (ET)</option>
            <option value="FK" >Falkland Islands (FK)</option>
            <option value="FO" >Faroe Islands (FO)</option>
            <option value="FJ" >Fiji (FJ)</option>
            <option value="FI" >Finland (FI)</option>
            <option value="FR" >France (FR)</option>
            <option value="GF" >French Guiana (GF)</option>
            <option value="PF" >French Polynesia (PF)</option>
            <option value="GA" >Gabon (GA)</option>
            <option value="GM" >Gambia (GM)</option>
            <option value="GE" >Georgia (GE)</option>
            <option value="DE" >Germany (DE)</option>
            <option value="GH" >Ghana (GH)</option>
            <option value="GI" >Gibraltar (GI)</option>
            <option value="GR" >Greece (GR)</option>
            <option value="GL" >Greenland (GL)</option>
            <option value="GD" >Grenada (GD)</option>
            <option value="GP" >Guadeloupe (GP)</option>
            <option value="GU" >Guam (GU)</option>
            <option value="GT" >Guatemala (GT)</option>
            <option value="GN" >Guinea (GN)</option>
            <option value="GW" >Guinea - Bissau (GW)</option>
            <option value="GY" >Guyana (GY)</option>
            <option value="HT" >Haiti (HT)</option>
            <option value="HN" >Honduras (HN)</option>
            <option value="HK" >Hong Kong (HK)</option>
            <option value="HU" >Hungary (HU)</option>
            <option value="IS" >Iceland (IS)</option>
            <option value="IN" >India (IN)</option>
            <option value="ID" >Indonesia (ID)</option>
            <option value="IR" >Iran (IR)</option>
            <option value="IQ" >Iraq (IQ)</option>
            <option value="IE" >Ireland (IE)</option>
            <option value="IL" >Israel (IL)</option>
            <option value="IT" >Italy (IT)</option>
            <option value="JM" >Jamaica (JM)</option>
            <option value="JP" >Japan (JP)</option>
            <option value="JO" >Jordan (JO)</option>
            <option value="KZ" >Kazakhstan (KZ)</option>
            <option value="KE" >Kenya (KE)</option>
            <option value="KI" >Kiribati (KI)</option>
            <option value="KP" >Korea North (KP)</option>
            <option value="KR" >Korea South (KR)</option>
            <option value="KW" >Kuwait (KW)</option>
            <option value="KG" >Kyrgyzstan (KG)</option>
            <option value="LA" >Laos (LA)</option>
            <option value="LV" >Latvia (LV)</option>
            <option value="LB" >Lebanon (LB)</option>
            <option value="LS" >Lesotho (LS)</option>
            <option value="LR" >Liberia (LR)</option>
            <option value="LY" >Libya (LY)</option>
            <option value="LI" >Liechtenstein (LI)</option>
            <option value="LT" >Lithuania (LT)</option>
            <option value="LU" >Luxembourg (LU)</option>
            <option value="MO" >Macao (MO)</option>
            <option value="MK" >Macedonia (MK)</option>
            <option value="MG" >Madagascar (MG)</option>
            <option value="MW" >Malawi (MW)</option>
            <option value="MY" >Malaysia (MY)</option>
            <option value="MV" >Maldives (MV)</option>
            <option value="ML" >Mali (ML)</option>
            <option value="MT" >Malta (MT)</option>
            <option value="MH" >Marshall Islands (MH)</option>
            <option value="MQ" >Martinique (MQ)</option>
            <option value="MR" >Mauritania (MR)</option>
            <option value="YT" >Mayotte (YT)</option>
            <option value="MX" >Mexico (MX)</option>
            <option value="FM" >Micronesia (FM)</option>
            <option value="MD" >Moldova (MD)</option>
            <option value="MC" >Monaco (MC)</option>
            <option value="MN" >Mongolia (MN)</option>
            <option value="MS" >Montserrat (MS)</option>
            <option value="MA" >Morocco (MA)</option>
            <option value="MZ" >Mozambique (MZ)</option>
            <option value="MN" >Myanmar (MN)</option>
            <option value="NA" >Namibia (NA)</option>
            <option value="NR" >Nauru (NR)</option>
            <option value="NP" >Nepal (NP)</option>
            <option value="NL" >Netherlands (NL)</option>
            <option value="NC" >New Caledonia (NC)</option>
            <option value="NZ" >New Zealand (NZ)</option>
            <option value="NI" >Nicaragua (NI)</option>
            <option value="NE" >Niger (NE)</option>
            <option value="NG" >Nigeria (NG)</option>
            <option value="NU" >Niue (NU)</option>
            <option value="NF" >Norfolk Islands (NF)</option>
            <option value="NP" >Northern Marianas (NP)</option>
            <option value="NO" >Norway (NO)</option>
            <option value="OM" >Oman (OM)</option>
            <option value="PW" >Palau (PW)</option>
            <option value="PA" >Panama (PA)</option>
            <option value="PG" >Papua New Guinea (PG)</option>
            <option value="PY" >Paraguay (PY)</option>
            <option value="PE" >Peru (PE)</option>
            <option value="PH" >Philippines (PH)</option>
            <option value="PL" >Poland (PL)</option>
            <option value="PT" >Portugal (PT)</option>
            <option value="PR" >Puerto Rico (PR)</option>
            <option value="QA" >Qatar (QA)</option>
            <option value="RE" >Reunion (RE)</option>
            <option value="RO" >Romania (RO)</option>
            <option value="RU" >Russia (RU)</option>
            <option value="RW" >Rwanda (RW)</option>
            <option value="SM" >San Marino (SM)</option>
            <option value="ST" >Sao Tome &amp; Principe (ST)</option>
            <option value="SA" >Saudi Arabia (SA)</option>
            <option value="SN" >Senegal (SN)</option>
            <option value="CS" >Serbia (CS)</option>
            <option value="SC" >Seychelles (SC)</option>
            <option value="SL" >Sierra Leone (SL)</option>
            <option value="SG" >Singapore (SG)</option>
            <option value="SK" >Slovak Republic (SK)</option>
            <option value="SI" >Slovenia (SI)</option>
            <option value="SB" >Solomon Islands (SB)</option>
            <option value="SO" >Somalia (SO)</option>
            <option value="ZA" >South Africa (ZA)</option>
            <option value="ES" >Spain (ES)</option>
            <option value="LK" >Sri Lanka (LK)</option>
            <option value="SH" >St. Helena (SH)</option>
            <option value="KN" >St. Kitts (KN)</option>
            <option value="SC" >St. Lucia (SC)</option>
            <option value="SD" >Sudan (SD)</option>
            <option value="SR" >Suriname (SR)</option>
            <option value="SZ" >Swaziland (SZ)</option>
            <option value="SE" >Sweden (SE)</option>
            <option value="CH" >Switzerland (CH)</option>
            <option value="SI" >Syria (SI)</option>
            <option value="TW" >Taiwan (TW)</option>
            <option value="TJ" >Tajikstan (TJ)</option>
            <option value="TH" >Thailand (TH)</option>
            <option value="TG" >Togo (TG)</option>
            <option value="TO" >Tonga (TO)</option>
            <option value="TT" >Trinidad &amp; Tobago (TT)</option>
            <option value="TN" >Tunisia (TN)</option>
            <option value="TR" >Turkey (TR)</option>
            <option value="TM" >Turkmenistan (TM)</option>
            <option value="TM" >Turkmenistan (TM)</option>
            <option value="TC" >Turks &amp; Caicos Islands (TC)</option>
            <option value="TV" >Tuvalu (TV)</option>
            <option value="UG" >Uganda (UG)</option>
            <option value="GB" >UK (GB)</option>
            <option value="UA" >Ukraine (UA)</option>
            <option value="AE" >United Arab Emirates (AE)</option>
            <option value="UY" >Uruguay (UY)</option>
            <option value="US" >USA (US)</option>
            <option value="UZ" >Uzbekistan (UZ)</option>
            <option value="VU" >Vanuatu (VU)</option>
            <option value="VA" >Vatican City (VA)</option>
            <option value="VE" >Venezuela (VE)</option>
            <option value="VN" >Vietnam (VN)</option>
            <option value="VG" >Virgin Islands - British (VG)</option>
            <option value="VI" >Virgin Islands - US (VI)</option>
            <option value="WF" >Wallis &amp; Futuna (WF)</option>
            <option value="YE" >Yemen (North)(YE)</option>
            <option value="YE" >Yemen (South)(YE)</option>
            <option value="ZM" >Zambia (ZM)</option>
            <option value="ZW" >Zimbabwe (ZW)</option>
          </select>
  		</td>
        <td><input type="text" placeholder="URL for redirection" name="redirect-to" style="width:100%"/></td>
  		<td>
          <input type="submit" name="hgr_add_loc" value="Add Location +" />
  			
  		</td>
  	</tr>
    </table>
    </form>

    <br>
    
    <table class="wp-list-table widefat fixed striped pages" cellpadding="2">
        <thead>
            <tr>
            <td class="manage-column column-cb check-column"><input type="checkbox" /></td>
            <th class="manage-column column-title column-primary sortable desc" scope="col"><b>Location</b></th>
            <th class="manage-column column-title column-primary sortable desc" scope="col"><b>Redirected URL</b></th>
            
            </tr>
        </thead>
        <tbody>
        <?php 
        $args = array('post_type' => 'hgr');
        $hgr_posts = get_posts( $args );
        foreach($hgr_posts as $post){

            
           $post_meta_loc = get_post_meta($post->ID,'hgr_location');
           $post_meta_redirect = get_post_meta($post->ID,'hgr_redirection');
        ?>
        <tr class="alternate">
            <th class="check-column" scope="row" width="10%"><input type="checkbox" /></th>
            <td class="title column-title has-row-actions column-primary page-title">
                <strong><?php echo $post_meta_loc[0]; ?></strong>
                <div class="row-actions">
                    <span class="trash"><a href="<?php echo admin_url('admin.php?page=hgr-top-level-handle&delete='.$post->ID); ?>">Delete</a></span>
                    
                </div>
            </td>
            <td class="column-columnname"><?php echo $post_meta_redirect[0]; ?></td>
            
               
            
        </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<?php } //hgr_admin_form() ends
?>