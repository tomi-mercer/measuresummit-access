<?php
/**
 * @package BuddyBoss Child
 * The parent theme functions are located at /buddyboss-theme/inc/theme/functions.php
 * Add your own functions at the bottom of this file.
 */


/****************************** THEME SETUP ******************************/

/**
 * Sets up theme for translation
 *
 * @since BuddyBoss Child 1.0.0
 */
function buddyboss_theme_child_languages()
{
  /**
   * Makes child theme available for translation.
   * Translations can be added into the /languages/ directory.
   */

  // Translate text from the PARENT theme.
  load_theme_textdomain( 'buddyboss-theme', get_stylesheet_directory() . '/languages' );

  // Translate text from the CHILD theme only.
  // Change 'buddyboss-theme' instances in all child theme files to 'buddyboss-theme-child'.
  // load_theme_textdomain( 'buddyboss-theme-child', get_stylesheet_directory() . '/languages' );

}
add_action( 'after_setup_theme', 'buddyboss_theme_child_languages' );

/**
 * Enqueues scripts and styles for child theme front-end.
 *
 * @since Boss Child Theme  1.0.0
 */
function buddyboss_theme_child_scripts_styles()
{
  /**
   * Scripts and Styles loaded by the parent theme can be unloaded if needed
   * using wp_deregister_script or wp_deregister_style.
   *
   * See the WordPress Codex for more information about those functions:
   * http://codex.wordpress.org/Function_Reference/wp_deregister_script
   * http://codex.wordpress.org/Function_Reference/wp_deregister_style
   **/

  // Styles
  wp_enqueue_style('bootstrap4', 'https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css');
  wp_enqueue_style( 'buddyboss-child-css', get_stylesheet_directory_uri().'/assets/css/custom.css', '', time() );

  // Javascript
  wp_enqueue_script( 'alpine-js', '//cdn.jsdelivr.net/gh/alpinejs/alpine@v2.3.5/dist/alpine.min.js', '', '1.0.0' );
	wp_enqueue_script( 'bootstrap4-js', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js', '', '1.0.0' );
  wp_enqueue_script( 'ResizeSensor', get_stylesheet_directory_uri().'/assets/js/ResizeSensor.js', '', time() );
  wp_enqueue_script( 'ElementQueries', get_stylesheet_directory_uri().'/assets/js/ElementQueries.js', '', time() );
  wp_enqueue_script( 'buddyboss-child-js', get_stylesheet_directory_uri().'/assets/js/custom.js', '', time() );
  wp_enqueue_style( 'load-fa', 'https://use.fontawesome.com/releases/v5.5.0/css/all.css' );
}
add_action( 'wp_enqueue_scripts', 'buddyboss_theme_child_scripts_styles', 9999 );




/****************************** CUSTOM FUNCTIONS ******************************/

// Add your own custom functions here
function wpb_remove_loginshake() {
  remove_action('login_head', 'wp_shake_js', 12);
}
add_action('login_head', 'wpb_remove_loginshake'); 

function my_login_stylesheet() {
  wp_enqueue_script( 'login-js', get_stylesheet_directory_uri().'/assets/js/login.js', '', time() );
  wp_enqueue_style( 'custom-login', get_stylesheet_directory_uri() . '/assets/css/login.css', '', time() );
}
add_action( 'login_enqueue_scripts', 'my_login_stylesheet' );

function get_dashboard_events($page_id){
    global $post;
    $post = get_post($page_id);
    setup_postdata( $post ); 
    $events = [];
    $current_event_day = [];
    $current_event_speaker = "";
    $next_event_speaker = "";
    $current_speaker_key = 0;
    $current_video_id = "";


    // Check rows exists.
    if( have_rows('day') ):

        // Loop through rows.
        $i = 0;
        while( have_rows('day') ) : the_row();

            $events[$i]["day_title"]       = get_sub_field('day');
            $events[$i]["is_active_event"] = get_sub_field('is_active_event');
            $events[$i]["event_date"]      = get_sub_field('event_date');

            $events[$i]["speakers"] = [];
            $speakers = [];
            $j = 0;
            while( have_rows('speakers') ) : the_row();

                
                $post_data = get_sub_field('speaker');
                $post_meta = get_post_meta($post_data->ID);
                $speaker = [ "post_data"   => $post_data, 
                            "avatar"       => get_the_post_thumbnail_url($post_data->ID),
                            "name"         => $post_data->post_title, 
                            "post_id"      => $post_data->ID,
                            "speaker_link" => get_permalink($post_data->ID) ];

                $speakers[$j]["speaker"] = $speaker;
                $speakers[$j]["time"] = get_sub_field('time');
                $speakers[$j]["topic"] = get_sub_field('topic');
                $speakers[$j]["presenting_now"] = get_sub_field('presenting_now');
                $speakers[$j]["youtube_id"] = get_sub_field('youtube_id');

                $j++;
            endwhile;

            $events[$i]["speakers"] = $speakers;

            $i++;
        endwhile;

    endif;

    foreach($events as $e_key=>$event){
        if($event["is_active_event"]){
             
            $current_event_day = $event;

            foreach($event["speakers"] as $speaker_key=>$speaker){
                if( $speaker["presenting_now"] ){
                    $current_event_speaker = $speaker;
                    $current_video_id      = $speaker["youtube_id"];
                    $next_event_speaker    = $event["speakers"][$speaker_key+1];
                }
            }

        }
    }
    
    $response = [  "events"            => $events,
                  "current_event_day" => $current_event_day,
                  "current_speaker"   => $current_event_speaker,
                  "next_speaker"      => $next_event_speaker,
                  "current_video_id"  => $current_video_id
                ];
          
    return $response;
}


add_action("wp_ajax_dashboard_player", function(){
  dashboard_player();
});
add_action("wp_ajax_nopriv_dashboard_player", function(){
  dashboard_player();
});

function dashboard_player(){
  $response = get_dashboard_events($_GET["dashboardid"]);
 
  echo json_encode($response);
  die();
}


function speaker_questions_list(){
    global $post;
    $post = get_post($_GET["speaker_post_id"]);
    setup_postdata( $post );
    
    while( have_rows('questions') ): the_row(); 
        $user  = get_sub_field('user');
        $user_meta = get_user_meta($user["ID"]);
        $user  = get_userdata($user["ID"]);
        $name  = $user_meta["first_name"][0]." ".$user_meta["last_name"][0];
        $roles = ( array ) $user->roles;
        
        $question  = get_sub_field('question');
        $date_time = get_sub_field('date_time');

        if(empty($user)){
            $name = get_sub_field('guest_name');
        }

        $answers = [];
        while( have_rows('answers') ): the_row();

            $answer_user      = get_sub_field('user');
            
            $answer_user_meta = get_user_meta($answer_user["ID"]);
            $answer_user_name = $answer_user_meta["first_name"][0]." ".$answer_user_meta["last_name"][0];
            
            $answers[] = [
              "answer" => get_sub_field("answer"),
              "name"   => $answer_user_name,
              "avatar" => get_avatar_url($answer_user["ID"]),
            ];
        endwhile;
      

        $questions[] = [
          "name"     => $name,
          "avatar"   => get_avatar_url($user->ID),
          "role"     => ucwords($roles[0]),
          "datetime" => date("F d, Y",strtotime($date_time))." at ". date("h:i a",strtotime($date_time)),
          "question" => $question,
          "guest"    => get_sub_field('guest_name'),
          "answers"  => $answers,
          "uuid"     => get_sub_field("speaker_question_uuid")
        ];
    endwhile;
    
    echo json_encode($questions);
    die();
}

add_action("wp_ajax_speaker_questionslist", function(){
  speaker_questions_list();
});
add_action("wp_ajax_nopriv_speaker_questionslist", function(){
  speaker_questions_list();
});


function access_submit_question(){
  $user_id = get_current_user_id();
  $guest_name = "";
  if(empty($user_id)){
    $guest_name = "Guest".mt_rand();
  }

  $question = $_POST["question"];
  $speaker_post_id = $_POST["speaker_post_id"];

  $success = add_row("questions",
    [
      "user"      => $user_id, 
      "question"  => $question, 
      "date_time" => date("Y-m-d H:i:s"),
      "guest_name" => $guest_name
    ], 
  $speaker_post_id);
}

add_action("wp_ajax_submit_question", function(){
  access_submit_question();
});
add_action("wp_ajax_nopriv_submit_question", function(){
  access_submit_question();
});


function submit_question_reply(){
    global $post;
    $user_id = get_current_user_id();
    $post = get_post($_POST["speaker_post_id"]);
    setup_postdata( $post ); 
    /* 
    [uuid] => 5f60efd12b29d
    [speaker_post_id] => 73
    [reply] => f
    if( have_rows('staff_members') ) {
      while( have_rows('staff_members') ) {
          the_row();

          // Get this staff member's name.
          $name = get_sub_field('name');

          // Update this staff member's first image.
          $value = array(
              'image' => 123,
              'alt'   => "The first photo of $name",
              'link'  => 'http://website.com'
          );
          update_sub_row('images', 1, $value);
      }
  }
    */

    while( have_rows('questions') ): the_row(); 
        $uuid = get_sub_field("speaker_question_uuid");
        if($uuid==$_POST["uuid"]){
          add_sub_row("answers", 
            [
              "answer"=> $_POST["reply"],
              "user"  => $user_id
            ]
            ,$_POST["speaker_post_id"]);
        }

      endwhile;
    
}
add_action("wp_ajax_submit_question_reply", function(){
  submit_question_reply();
});
add_action("wp_ajax_nopriv_submit_question_reply", function(){
  submit_question_reply();
});


add_shortcode("test_pbd", function(){
  return "test";
});


function my_acf_load_field(  $value, $post_id, $field  ) {
    
  $uuid = uniqid();    
  if (empty($value))
  {
      $value = $uuid;
  }
  return $value;
       
}

add_filter('acf/update_value/name=speaker_question_uuid', 'my_acf_load_field', 10, 3);

function ms_login_css() { ?>
    <style type="text/css">
       .login-action-checkemail #login > p.message {
    margin-top: 0 !important;
    border-radius: 0 0 10px 10px!important;
    color: #000 !important;
    padding: 20px 30px 50px !important;
}
		.login-action-checkemail #login > p.message{
			display:none !important;
		}
		.login-action-checkemail p.new-message {
    background: #fff;
    padding: 30px 10px 50px;
    text-align: center;
    border-radius: 0 0 10px 10px;
}
		.login-action-checkemail .login-heading{
			padding:30px 20px 0 !important;
			    text-align: center;
		}
		.login-action-checkemail .login-heading h2{
			font-size:26px !important;
		}
.login-action-checkemail #login > p.message a {
    color: #000 !important;
}
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'ms_login_css' );

add_filter( 'login_message', 'ms_custom_login_message' );
function ms_custom_login_message( $message ) {
    $action = isset( $_REQUEST['action'] ) ? $_REQUEST['action'] : '';
    $errors = new WP_Error();
 
    if ( isset( $_GET['key'] ) ) {
        $action = 'resetpass';
    }
 
    if ( isset( $_GET['checkemail'] ) ) {
        $action = 'checkemail';
    }
	
	 if ( $action == 'checkemail' ) {
		$message .= '<div class="login-heading"><h2>' . __( 'Success!  Check Your Email For the Confirmation Link...', 'text_domain' ) . '</h2></div>';
        $message .= '<p class="new-message">' . __( '(It should arrive within 2 minutes, be sure to check spam/junk mail folders.)', 'text_domain' ) . '</p>';
    } 
 
    return $message;
}
?>