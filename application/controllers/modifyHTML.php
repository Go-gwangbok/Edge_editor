<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class modifyHTML extends CI_Controller {

	function __construct(){
		parent::__construct();		
		$this->load->database();
	}
	
	function modifyHTML($original_texts, $edited_texts)
    {
    	$modified_texts = array();

		$original_texts = explode(' ', $original_texts);

		$edited_texts = explode(' ', $edited_texts);
		
		foreach($original_texts as $original_text)
		{
			$temp_edited_texts = array();
			foreach ($edited_texts as $edited_text) 
			{
				if($original_text == $edited_text) // equal
				{
					if(count($temp_edited_texts) > 0)
					{
						array_push($modified_texts, '<ins>'.implode(' ', $temp_edited_texts).'</ins>');
						$temp_edited_texts = array();
					}
					array_push($modified_texts, $edited_text);
					array_shift($original_texts);
					array_shift($edited_texts);
					break 1;
				}
				else if(url_title($original_text, '', TRUE) == url_title($edited_text, '', TRUE)) // url equal
				{
					$new_edited_text = str_replace(array('.', ',', '?', '!'), array('<ins>.</ins>', '<ins>,</ins>', '<ins>?</ins>', '<ins>!</ins>'), $edited_text);
					if($edited_text != $new_edited_text)
					{
						if(count($temp_edited_texts) > 0)
						{
							array_push($modified_texts, '<ins>'.implode(' ', $temp_edited_texts).'</ins>');
							$temp_edited_texts = array();
						}
						array_push($modified_texts, $new_edited_text);
					}
					else
					{
						if(count($temp_edited_texts) > 0)
						{
							array_push($modified_texts, '<ins>'.implode(' ', $temp_edited_texts).'</ins>');
							$temp_edited_texts = array();
						}
						array_push($modified_texts, '<del>'.$original_text.'</del>');
						array_push($modified_texts, '<ins>'.$edited_text.'</ins>');
					}
					array_shift($original_texts);
					array_shift($edited_texts);
					break 1;
				}
				else
				{
					$root = false;
					if((strtolower($original_text) == 'a' && strtolower($edited_text) == 'the') || (strtolower($original_text) == 'the' && strtolower($edited_text) == 'a'))
					{
						$root = true;
					}
					else
					{
						$o_url = 'http://54.249.233.215/index.php/search/pos_compare/?from='.$original_text.'&to='.$edited_text;
						$o_result = $this->curl->simple_get($o_url);
						$o_json_result = json_decode($o_result, TRUE);

						if($o_json_result['status'] == true)
						{
							$root = true;
						}
					}

					if($root)
					{
						if(count($temp_edited_texts) > 0)
						{
							array_push($modified_texts, '<ins>'.implode(' ', $temp_edited_texts).'</ins>');
							$temp_edited_texts = array();
						}
						array_push($modified_texts, '<del>'.$original_text.'</del>');
						array_push($modified_texts, '<ins>'.$edited_text.'</ins>');
						array_shift($original_texts);
						array_shift($edited_texts);
						break 1;
					}
					else
					{
						similar_text($original_text, $edited_text, $ratio);
						if($ratio > 80) // similar
						{
							if(count($temp_edited_texts) > 0)
							{
								array_push($modified_texts, '<ins>'.implode(' ', $temp_edited_texts).'</ins>');
								$temp_edited_texts = array();
							}
							array_push($modified_texts, '<del>'.$original_text.'</del>');
							array_push($modified_texts, '<ins>'.$edited_text.'</ins>');
							array_shift($original_texts);
							array_shift($edited_texts);
							break 1;
						}
						else
						{
							array_push($temp_edited_texts, $edited_text);
							array_shift($edited_texts);
						}
					}
				}
			}
			if(count($temp_edited_texts) > 0)
			{
				array_push($modified_texts, '<del>'.$original_text.'</del>');
				array_shift($original_texts);
				$edited_texts = $temp_edited_texts;
			}
		}

		$result = implode(' ', $modified_texts).'<del>'.trim(implode(' ', $original_texts)).'</del> <ins>'.trim(implode(' ', $edited_texts)).'</ins>';

		$result = str_replace(array('</ins> <ins>', '</del> <del>', '<ins></ins>', '<del></del>'), ' ', $result);

		return trim($result);
    }


    function make_edit_html($original_texts, $edited_texts)
	{
		$original_texts = str_replace('(?)', '', $original_texts);
		$edited_texts = str_replace('(?)', '', $edited_texts);

		$original_texts = $this->explodeX(array(', ', '. ', '? ', '! ', 'and '), $original_texts);
		$edited_texts = $this->explodeX(array(', ', '. ', '? ', '! ', 'and '), $edited_texts);

		$return_string = '';
		foreach ($edited_texts as $edited_text) 
		{
			if(($key = array_search($edited_text, $original_texts, TRUE)) !== FALSE)
			{
		        unset($original_texts[$key]);
		    }
		}

		foreach ($edited_texts as $edited_text) 
		{
			$editeds = explode('_', url_title($edited_text, '_', TRUE));

			$diff_count = 0;
			$original = '';

			foreach ($original_texts as $original_text) 
			{
				$originals = explode('_', url_title($original_text, '_', TRUE));

				$diffs_1 = count(array_diff($editeds, $originals));
				$diffs_2 = count(array_diff($originals, $editeds));
				if($diff_count == 0)
				{
					$diff_count = $diffs_1 + $diffs_2;
					$original = $original_text;
				}
				else
				{
					if($diffs_1 + $diffs_2 < $diff_count)
					{
						$diff_count = $diffs_1 + $diffs_2;
						$original = $original_text;
						if(($key = array_search($original_text, $original_texts, TRUE)) !== FALSE){
					        unset($original_texts[$key]);
					    }
					}
				}
			}

			if($diff_count != 0 && $diff_count / count($editeds) < 1.1 && strlen($original) > 10) // $diff_count / count($editeds) < 1
			{
				$return_string = $return_string.'<div class=\'sentence\'><p class=\'editor_html1\'>'.$original.'</p><img class=\'edit_icon\' /><p class=\'editor_html2\'> '.$edited_text.'</p></div>';
			}
		}

		return $return_string;
	}
}
