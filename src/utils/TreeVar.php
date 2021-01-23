<?php

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
		    /*.Node {
		        margin-left: 18px;
		        zoom: 1;
		        линия слева образуется повторяющимся фоновым рисунком 
		        background-image : url(/forum/img/i.gif);
		        background-position : top left;
		        background-repeat : repeat-y;
		    }*/
		</style>
			<div class="treevar"><!--
    		   --><div class="dParent '.($open_all?'':'dClose').'"><!--
        	   --><div class="dInline"><div class="dInlineBlock dSquare dBtnOpen">'.($open_all?'-':'+').'</div>&nbsp;TreeVar</div><!--
        	   --><div class="dChilds '.($open_all?'':'dNone').'">';	
		$openVar = function ($k, $v, $openVar, $open_all){
			 if (is_array($v)) {
			 	$type = 'a';
			 	if (empty($v)) {
					return '<div class="dVdash"></div><div class="dInline">'.((empty($k)&&$k!==0)?(''):('['.$k.'] : ')).(gettype($v)).'()</div><br />';
			 	} else {
			 		$s = '
			 		<div class="dParent '.($open_all?'':'dClose').'"><div class="dVdash dInline"></div><!--
                	  --><div class="dInline"><div class="dInlineBlock dSquare dBtnOpen">'.($open_all?'-':'+').'</div>&nbsp;';
                	$s .= ((empty($k)&&$k!==0)?(''):('['.$k.'] : ')).(gettype($v));
                	$s .= '</div><div class="dChilds dStep '.($open_all?'':'dNone').'">';
                }
			 } else if (is_object($v)) {
			 	$type = 'o';
			 	$s = '
			 	<div class="dParent '.($open_all?'':'dClose').'"><div class="dVdash dInline"></div><!--
                   --><div class="dInline"><div class="dInlineBlock dSquare dBtnOpen">'.($open_all?'-':'+').'</div>&nbsp;';
				$s .= ((empty($k)&&$k!==0)?(''):('['.$k.'] : ')).(get_class($v));
                $s .= '</div><div class="dChilds dStep '.($open_all?'':'dNone').'">';
				// $v = get_class_vars(get_class($v));
				$ref = new \ReflectionObject($v);
				$vv = [];
				foreach ($ref->getProperties() as $prop)
				{
				    $prop->setAccessible(true);
				    $vv[$prop->getName()]=$prop->getValue($v);
				};
				$v = $vv;
			 } else if (is_resource($v)) {
			 	$type = 'r';
			 	return '<div class="dVdash"></div><div class="dInline">'.((empty($k)&&$k!==0)?(''):('['.$k.'] : ')).(get_resource_type($v)).'</div><br />';
			 } else {
			 	return '<div class="dVdash"></div><div class="dInline">'.((empty($k)&&$k!==0)?(''):('['.$k.'] : ')).(gettype($v)).' =&gt; '.(strval($v)).'</div><br />';
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
			        }
			        var debugs = document.getElementsByClassName("treevar");
			        if (debugs.length > 1)
            			debugs = [debugs[debugs.length-1]];
			        var i_debugs = 0, l_debugs = debugs.length, parents, i, l;
			        while (i_debugs < l_debugs) { 
			            parents = debugs[i_debugs].getElementsByClassName("dBtnOpen");
			            i =0, l = parents.length;
			            while (i < l) {
			                addListener(parents[i]);
			                ++i;
			            }
			            ++i_debugs;
			        }
			    })();
			</script>';
		// echo preg_replace('/\>\s+\</m', '><',$result);
			echo $result;
	}

?>