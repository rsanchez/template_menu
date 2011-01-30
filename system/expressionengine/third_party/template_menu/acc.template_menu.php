<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Template_menu_acc
{
	public $name = 'Template Menu';
	public $id = 'template_menu_acc';
	public $version = '1.0.0';
	public $description = '';
	public $sections = array();
	
	private $always_open = TRUE;

	public function __construct()
	{
		$this->EE = get_instance();
	}
	
	public function set_sections()
	{
		$this->EE->load->model('template_model');
		$this->EE->load->helper(array('html', 'url'));
		$this->EE->lang->loadfile('design');
		
		$query = $this->EE->template_model->get_templates(NULL, array('templates.group_id'));
		
		$sections = array();
		$groups = array();
		
		foreach ($query->result() as $row)
		{
			$groups[$row->group_name] = $row->group_id;
			$sections[$row->group_name][] = anchor(BASE.AMP.'C=design'.AMP.'M=edit_template'.AMP.'id='.$row->template_id, $row->template_name);
		}
		
		$search_form = form_open('C=design'.AMP.'M=manager');
		$search_form .= form_input(array(
			'type' => 'search',
			'id' => 'template_keywords',
			'name' => 'template_keywords',
			'value' => '',
			'maxlength' => '80',
			'class' => 'input',
			'autosave' => 'ee_template_search',
			'results' => '10',
			'placeholder' => 'Search Template',
			'style' => 'width:140px'
		));
		
		$search_form .= form_close();
		
		$this->sections[''] = ul(array(
			$search_form,
			anchor(BASE.AMP.'C=design'.AMP.'M=new_template_group', '+ '.$this->EE->lang->line('nav_create_group')),
			anchor(BASE.AMP.'C=design'.AMP.'M=global_variables', $this->EE->lang->line('nav_global_variables')),
			anchor(BASE.AMP.'C=design'.AMP.'M=snippets', $this->EE->lang->line('nav_snippets')),
			anchor(BASE.AMP.'C=design'.AMP.'M=sync_templates', $this->EE->lang->line('nav_sync_templates')),
			anchor(BASE.AMP.'C=design'.AMP.'M=global_template_preferences', $this->EE->lang->line('global_template_preferences')),
			anchor(BASE.AMP.'C=design'.AMP.'M=template_preferences_manager', $this->EE->lang->line('template_preferences_manager'))
		)).'<div class="clear"></div>';
		
		foreach ($sections as $key => $value)
		{
			array_push($value, anchor(BASE.AMP.'C=design'.AMP.'M=new_template'.AMP.'group_id='.$groups[$key], '<span class="notice">+</span>'));
			$this->sections[anchor(BASE.AMP.'C=design'.AMP.'M=edit_template_group'.AMP.'group_id='.$groups[$key], $key)] = ul($value);
		}
		
		$this->EE->cp->add_to_head('<style type="text/css">#template_menu_acc{padding:10px !important;}#template_menu_acc .accessorySection{padding:0 5px;}#template_menu_acc .accessorySectionFirst{float:none;border:none;border:0;margin-bottom:10px;line-height:20px;}#template_menu_acc .accessorySectionFirst li{float:left;margin-right:14px;}#template_menu_acc .accessorySectionFirst h5{display:none;}#template_menu_acc li a{display:block;padding:0 0 3px;}#template_menu_acc li{padding:0 !important;}#template_menu_acc h5{font-size:12px !important;margin-bottom:4px !important;}#template_menu_acc .accessorySection h5 a{color:white !important;}</style>');
		$this->EE->javascript->output('$("#template_menu_acc .accessorySection:first").addClass("accessorySectionFirst");');
		
		if ($this->always_open)
		{
			$this->EE->javascript->output('
				$(".template_menu_acc").parent().siblings().removeClass("current");
				$("#accessoriesDiv .accessory").hide();
				$(".template_menu_acc").parent().addClass("current");
				$("#template_menu_acc").show();
			');
		}
	}
}