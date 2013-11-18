<?php

class SPL_Mailgun_Newsletter_Settings {

	private $options;

	function __construct() {
		add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
    add_action( 'admin_init', array( $this, 'page_init' ) );
	}
	
	public function add_plugin_page() {
	  add_options_page(
      'SPL Newsletter Settings' 						// page titlebar
      ,'SPL Newsletter'											// menu label
      ,'manage_options'											// capability
      ,'spl-mailgun-newsletter-settings' 		// menu slug
      ,array( $this, 'create_admin_page' ) 	// callback
	  );
  }

  public function create_admin_page() {
  	$this->options = get_option( 'spl-mailgun-newsletter-settings' );
  	//echo print_r($this->options, true);

    echo '<div class="wrap">';
    screen_icon();
    echo '<h2>SPL Newsletter Settings</h2>';        
    echo '<form method="post" action="options.php">';
    // hidden setting fields
    settings_fields( 'spl-mailgun-newsletter-settings-group' );   
    // user settings fields
    do_settings_sections( 'spl-mailgun-newsletter-settings' );
    submit_button();         
    echo '</form>';
    echo '</div>';    
  }
  
  public function page_init()
  {        
      register_setting(
      	'spl-mailgun-newsletter-settings-group' // Option group
      , 'spl-mailgun-newsletter' 								// Option name
      ,	array( $this, 'sanitize' ) 							// Sanitize
      );

      add_settings_section(
      	'spl-mailgun-newsletter-api' 						// ID
      ,	'Mailgun Api' 													// Title
      ,	array( $this, 'print_section_info' ) 		// Callback
      ,	'spl-mailgun-newsletter-settings' 			// Page
      );  

      add_settings_field(
	      'mailgun-public-key' 										// ID
      ,	'Public Key' 														// Title 
      ,	array( $this, 'public_key_callback' ) 	// Callback
      ,	'spl-mailgun-newsletter-settings' 			// Page
      ,	'spl-mailgun-newsletter-api' 						// Section           
      );      

      add_settings_field(
      	'mailgun-private-key' 									// ID
      ,	'Private Key' 													// Title
      ,	array( $this, 'private_key_callback' ) 	// Callback
      ,	'spl-mailgun-newsletter-settings' 			// Page
      ,	'spl-mailgun-newsletter-api'						// Section
      );      
  }

  /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
        if( isset( $input['id_number'] ) )
            $new_input['id_number'] = absint( $input['id_number'] );

        if( isset( $input['title'] ) )
            $new_input['title'] = sanitize_text_field( $input['title'] );

        return $new_input;
    }

    /** 
     * Print the Section text
     */
    public function print_section_info()
    {
        print 'Enter your settings below:';
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function public_key_callback($args)
    {
        printf(
            '<input type="text" id="id_number" name="my_option_name[id_number]" value="%s" />',
            isset( $this->options['id_number'] ) ? esc_attr( $this->options['id_number']) : ''
        );
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function private_key_callback()
    {
        printf(
            '<input type="text" id="title" name="my_option_name[title]" value="%s" />',
            isset( $this->options['title'] ) ? esc_attr( $this->options['title']) : ''
        );
    }

} // SPL_Mailgun_Newsletter_Settings

?>