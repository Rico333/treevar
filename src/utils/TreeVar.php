<?php
if (!function_exists('treevar')) {
	function treevar ($var, $open_all=false) { 
		$result = '
		<style>
			.treevar {
   			    display: block;
   			    font-family: monospace;
   			    /*white-space: pre;
   			    margin: 1em 0;*/
   			    background-color: #333333;
				color: #EEEEEE;
   			}
		    .treevar .dInline {
		        display: inline;
		        padding: 1px;
		    }
		    .treevar .dInlineBlock {
		        display: inline-block;
		        padding: 1px;
		    }
		    .treevar .dSquare {
		        /*padding: 0px 2px 0px 2px;*/
		        width: 19px;
		        height: 19px;
		        text-align: center;
		        border: solid #777777 1px;
		        cursor: pointer;
		    }
		    .treevar .dEmpty {
		        min-width: 20px;
		        min-height: 20px;
		        width: 20px;
		        height: 20px;
		        text-align: center;
		    }
		    .treevar .dStep {
		        /*text-indent: 2em;*/
		        margin-left: 20px;
		    }
		    .treevar .dVdash {
		        display: inline-block;
		        width: 20px;
		        height: 20px;
		        text-align: center;
		    }
		    /*.dVdashParent {
		        display: inline-block;
		        width: 20px;
		        height: 20px;
		        text-align: right;
		    }*/
		    .treevar .dNone {
		        display: none;
		    }
		    .treevar .dc_orange {
		        color: #ffa500;
		    }
		    .treevar .dc_blue {
		        color: #00ffff;
		    }
		    .treevar .dc_purple {
		        color: #ff00ff;
		    }
		    .treevar .dc_yellow {
		        color: #ffff00;
		    }
		    .treevar .dc_red {
		        color: #ff0000;
		    }
		    .treevar .dc_green {
		        color: #01ff20;
		    }
		    .treevar .dc_salad {
		        color: #04c89b;
		    }
		    /*.Node {
		        margin-left: 18px;
		        zoom: 1;
		        линия слева образуется повторяющимся фоновым рисунком 
		        background-image : url(/forum/img/i.gif);
		        background-position : top left;
		        background-repeat : repeat-y;
		    }*/
		</style>
			<div class="treevar'.($open_all?'':' dClose').'"><!--
    		   --><div class="dParent'.($open_all?'':' dClose').'"><!--
        	   --><div class="dInline"><div class="dInlineBlock dSquare dBtnOpenAll">'.($open_all?'c':'o').'</div>&nbsp;TreeVar&nbsp;<div class="dInlineBlock dSquare dBtnOpen">'.($open_all?'-':'+').'</div></div><!--
        	   --><div class="dChilds '.($open_all?'':'dNone').'">';	
		$openVar = function ($k, $v, $openVar, $open_all){
			if (is_string($k)) {
			 	$key_color = 'dc_green';
			 } else if (is_numeric($k)) {
				$key_color = 'dc_blue';
			 } else {
			 	$key_color = '';
			 }
			 if (empty($k) && $k !== 0) {
				$d_key = '';
			 } else {
			 	$d_key = '[<span class="'.$key_color.'">'.$k.'</span>]&nbsp;:&nbsp;';
			 }
			 if (is_array($v)) {
			 	$type = 'a';
			 	if (empty($v)) {
					return '<div class="dVdash"></div><div class="dInline">'.$d_key.'<span class="dc_yellow">'.(gettype($v)).'</span>&nbsp;=&gt;&nbsp;(<span class="dc_blue">0</span>)</div><br />';
			 	} else {
			 		$s = '
			 		<div class="dParent'.($open_all?'':' dClose').'"><div class="dVdash dInline"></div><!--
                	  --><div class="dInline">';
                	$s .= $d_key.'<span class="dc_yellow">'.(gettype($v)).'</span>';
                	$s .= '&nbsp;(<span class="dc_blue">'.count($v).'</span>)&nbsp;=&gt;&nbsp;<div class="dInlineBlock dSquare dBtnOpen">'.($open_all?'-':'+').'</div></div><div class="dChilds dStep '.($open_all?'':'dNone').'">';
                }
			 } else if (is_object($v)) {
			 	$type = 'o';
			 	// $v = get_class_vars(get_class($v));
				$ref = new \ReflectionObject($v);
				$vv = [];
				foreach ($ref->getProperties() as $prop)
				{
				    $prop->setAccessible(true);
				    $vv[$prop->getName()]=$prop->getValue($v);
				};
			 	$s = '
			 	<div class="dParent'.($open_all?'':' dClose').'"><div class="dVdash dInline"></div><!--
                   --><div class="dInline">';
				$s .= $d_key.'<span class="dc_orange">'.(get_class($v)).'</span>';
                $s .= '&nbsp;(<span class="dc_blue">'.count($vv).'</span>)&nbsp;=&gt;&nbsp;<div class="dInlineBlock dSquare dBtnOpen">'.($open_all?'-':'+').'</div></div><div class="dChilds dStep '.($open_all?'':'dNone').'">';
				$v = $vv;
			 } else if (is_resource($v)) {
			 	$type = 'r';
			 	return '<div class="dVdash"></div><div class="dInline">'.$d_key.'<span class="dc_salad">'.(get_resource_type($v)).'</span>&nbsp;=&gt;&nbsp;<span class="dc_salad">'.(strval($v)).'</span></div><br />';
			 } else {
			 	$str_val = strval($v);
			 	if (is_string($v)) {
			 		$v_color = 'dc_green';
			 	} else if (is_bool($v)) {
			 		if ($v === true) $str_val = 'true'; else if ($v === false) $str_val = 'false';
					$v_color = 'dc_purple';
			 	} else if (is_null($v)) {
			 		$str_val = 'null';
					$v_color = 'dc_red';
			 	} else if (is_numeric($v)) {
					$v_color = 'dc_blue';
			 	} else {
			 		$v_color = '';
			 	}
			 	$type_color = '';
			 	$type_value = gettype($v);
			 	switch (gettype($v)) {
			 		case ('string'):   $type_color = 'dc_green'; break;
			 		case ('array'):    $type_color = 'dc_yellow'; break;
			 		case ('object'):   $type_color = 'dc_orange'; break;
			 		case ('bool'):     $type_color = 'dc_purple'; break;
			 		case ('boolean'):  $type_color = 'dc_purple'; break;
			 		case ('integer'):  $type_color = 'dc_blue'; break;
			 		case ('int'):      $type_color = 'dc_blue'; break;
			 		case ('float'):    $type_color = 'dc_blue'; break;
			 		case ('double'):   $type_color = 'dc_blue'; break;
			 		case ('resource'): $type_color = 'dc_red'; break;
			 		case ('NULL'):     $type_color = 'dc_red'; break;
			 	}
			 	return '<div class="dVdash"></div><div class="dInline">'.$d_key.'<span class="'.$type_color.'">'.(gettype($v)).'</span>&nbsp;=&gt;&nbsp;<span class="'.$v_color.'">'.$str_val.'</span></div><br />';
			 }
			 foreach ($v as $key => $value) {
			 	$s .= $openVar($key, $value, $openVar, $open_all);
			 }
			 $s .= '</div></div>';
			 return $s;

		};
		$result .= $openVar('',$var, $openVar, $open_all);

		$result .= '</div></div></div>
			<script>
			    (function () {
			    	var attach_listeners = function (tree_var) {
			    		
			        	var addListener = function (b) {
			        	    b.addEventListener("click", function (e) {
			        	        if (event.target != b)
			        	            return; 
			        	        e.preventDefault();
			        	        var parent = b.parentNode.parentNode;
			        	        var child = parent.getElementsByClassName("dChilds")[0];
			        	        child.classList.remove("dNone");
			        	        if (parent.classList.contains("dClose")) {
			        	            parent.classList.remove("dClose");
			        	            parent.classList.add("dOpen");
			        	            b.innerHTML = "-";
			        	        } else {
			        	            child.classList.add("dNone");
			        	            parent.classList.remove("dOpen");
			        	            parent.classList.add("dClose");
			        	            b.innerHTML = "+";
			        	        }
			        	    });
			        	};
			        	var addListener_open_all = function (b) {
			        	    b.addEventListener("click", function (e) {
			        	        if (event.target != b)
			        	            return; 
			        	        e.preventDefault();
			        	        var parents    = tree_var.getElementsByClassName("dParent");
			        	        var childs     = tree_var.getElementsByClassName("dChilds");
			        	        var dBtnOpens  = tree_var.getElementsByClassName("dBtnOpen");
			        	        var i,l;
	
			        	        if (tree_var.classList.contains("dClose")) {
			        	        	i = 0, l = parents.length;
			        	        	while (i < l) {
			        	        		parents[i].classList.remove("dClose", "dOpen");
			        	            	parents[i].classList.add("dOpen");
			        	        		++i;
			        	        	}
			        	        	i = 0, l = childs.length;
			        	        	while (i < l) {
			        	        		if (childs[i].classList.contains("dNone")) {
			        	        			childs[i].classList.remove("dNone");
			        	        		}
			        	        		++i;
			        	        	}
			        	        	i = 0, l = dBtnOpens.length;
			        	        	while (i < l) {
			        	        		dBtnOpens[i].innerHTML = "-";
			        	        		++i;
			        	        	}
 									tree_var.classList.remove("dClose");
 									b.innerHTML = "c";
			        	        } else {
			        	        	i = 0, l = parents.length;
			        	        	while (i < l) {
			        	        		parents[i].classList.remove("dClose", "dOpen");
			        	            	parents[i].classList.add("dClose");
			        	        		++i;
			        	        	}
			        	        	i = 0, l = childs.length;
			        	        	while (i < l) {
			        	        		if (!childs[i].classList.contains("dNone")) {
			        	        			childs[i].classList.add("dNone");
			        	        		}
			        	        		++i;
			        	        	}
			        	        	i = 0, l = dBtnOpens.length;
			        	        	while (i < l) {
			        	        		dBtnOpens[i].innerHTML = "+";
			        	        		++i;
			        	        	}
									tree_var.classList.add("dClose");
									b.innerHTML = "o";
			        	        }
			        	    });
			        	};
			        	
			        	parents = tree_var.getElementsByClassName("dBtnOpen");
			        	i = 0, l = parents.length;
			        	while (i < l) {
			        	    addListener(parents[i]);
			        	    ++i;
			        	}
			        	parents = tree_var.getElementsByClassName("dBtnOpenAll");
			        	i = 0, l = parents.length;
			        	while (i < l) {
			        	    addListener_open_all(parents[i]);
			        	    ++i;
			        	}
			    	};
			    	if (!window.swer_treevar_debug_html_containers) {
			    		window.swer_treevar_debug_html_containers = [];
			    	};
			    	var tree_vars = document.getElementsByClassName("treevar");
					if (tree_vars) {
						var i_tv = 0, l_tv = tree_vars.length, i_c, l_c = window.swer_treevar_debug_html_containers.length;
						while (i_tv < l_tv) {
							i_c = 0;
							while (i_c < l_c) {
								if (window.swer_treevar_debug_html_containers[i_c] == tree_vars[i_tv]) {
									break;
								}
								++i_c;
							}
							if (i_c >= l_c) {
								attach_listeners(tree_vars[i_tv]);
								window.swer_treevar_debug_html_containers.push(tree_vars[i_tv]);
							}
							++i_tv;
						}
					};
			    	if (tree_vars.length > 0) {
            			tree_var = tree_vars[tree_vars.length-1];
			    	} else {
            			return;
			    	};

			    })();
			</script>';
		// echo preg_replace('/\>\s+\</m', '><',$result);
			echo $result;
	}
}
?>