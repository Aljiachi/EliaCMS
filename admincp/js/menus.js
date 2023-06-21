
// Active Menu function 
function activeMenu($event , $id){
			
		$.post("ajax/menus.php?action=active" , {
			"sender" : 1 , 
			"resortPost" : 1 , 
			"event" : $event , 
			"menuid" :  $id} , function($date){
								
				switch($event){
				
					case "enable" : 
						$("#enable-menu-" + $id).find("img").attr("src" , "icons/off.png");
						$("#enable-menu-" + $id).attr("onClick" , "activeMenu(\'disable\' , "+$id+")");
						$("#enable-menu-" + $id).attr("title" , _lang("DISABLE"));
						$("#enable-menu-" + $id).attr("id" , "disable-menu-" + $id);
						$("#menuBox-" + $id).removeClass("menuDisabled");			
					break;	
					case "disable" : 
						$("#disable-menu-" + $id).find("img").attr("src" , "icons/on.png");
						$("#disable-menu-" + $id).attr("onClick" , "activeMenu(\'enable\' , "+$id+")");
						$("#disable-menu-" + $id).attr("title" ,  _lang("ENABLE"));
						$("#disable-menu-" + $id).attr("id" , "enable-menu-" + $id);
						$("#menuBox-" + $id).addClass("menuDisabled");			
					break;	
				}
			});		
}

$(document).ready(function(){

	$("#menuUp,#menuDown").click(function(){
		   
			var row = $(this).parents("div:first");
			
			var rows= $("#menuTable").find("tr");
			
			var rowItem = row.find("div");
			
			if ($(this).is("#menuUp")) {
							
				if(row.prev().find("div").attr("data-type") !== "0"){
					
					var $sort = parseInt(rowItem.attr("data-sort"))+ parseInt(1);
					var $id   = parseInt(rowItem.attr("data-id"));
					var $type   = parseInt(rowItem.attr("data-type"));
														
					$.post("ajax/menus.php?action=resort" , {
						"sender" : 1 , 
						"resortPost" : 1 , 
						"sort" : $sort , 
						"menuid" : $id
						} , function($date){

						var PrevSort = parseInt(row.prev().find("div").attr("data-sort")) - parseInt(1);
						
						$.post("ajax/menus.php?action=resort" , {
							"sender" : 1 , 
							"resortPost" : 1 , 
							"sort" : PrevSort , 
							"menuid" : parseInt(row.prev().find("div").attr("data-id"))
							} , function($date){
					
								row.prev().find("div").attr("data-sort" , PrevSort);
									
							});
							
							rowItem.attr("data-sort" , $sort);
							row.insertBefore(row.prev()).hide().fadeIn(300);
								
						});
						
			
				}
				
			} else {
			
				if(row.next().attr("data-type") !== "0"){

					var $sort = parseInt(rowItem.attr("data-sort")) - parseInt(1);
					var $id   = rowItem.attr("data-id");
										
					$.post("ajax/menus.php?action=resort" , {
						"sender" : 1 , 
						"resortPost" : 1 , 
						"sort" : $sort , 
						"menuid" : $id
						} , function($date){

						var PrevSort = parseInt(row.next().find("div").attr("data-sort")) + parseInt(1);
						
						$.post("ajax/menus.php?action=resort" , {
							"sender" : 1 , 
							"resortPost" : 1 , 
							"sort" : PrevSort , 
							"menuid" : parseInt(row.next().find("div").attr("data-id"))
							} , function($date){
					
								row.next().find("div").attr("data-sort" , PrevSort);
									
							});
							
							rowItem.attr("data-sort" , $sort);						
							row.insertAfter(row.next()).hide().fadeIn(300);
								
						});
											
				}
				
			}
	   
	});
	
});
$(function() {
	$( ".menuBox" ).draggable({
			appendTo: "body",
			revert: "invalid",
			helper : 'clone'
		});

	$( ".rightMenusArea" ).droppable({
			activeClass: "ui-state-default",
			hoverClass: "ui-state-hover",
			accept: ":not(.ui-sortable-helper)",
			drop: function( event, ui ) {
				var selectedItem =  ui.draggable.find( "div" );
				if(selectedItem.attr("data-type") !== "1"){

					$(ui.draggable).fadeOut();

					$.post("ajax/menus.php?action=move" , {
						"sender" : 1 , 
						"resortPost" : 1 , 
						"align" : 1 , 
						"menuid" : selectedItem.attr("data-id")
						});
						
					$( "<div class=\"menuBox  ui-draggable\"></div>" ).html( ui.draggable.html() ).appendTo( this );
			
				}
			}
		});
	
	$( ".leftMenusArea" ).droppable({
			activeClass: "ui-state-default",
			hoverClass: "ui-state-hover",
			accept: ":not(.ui-sortable-helper)",
			drop: function( event, ui ) {
				var selectedItem =  ui.draggable.find( "div" );
				if(selectedItem.attr("data-type") !== "3"){

					$(ui.draggable).fadeOut();

					$.post("ajax/menus.php?action=move" , {
						"sender" : 1 , 
						"resortPost" : 1 , 
						"align" : 3 , 
						"menuid" : selectedItem.attr("data-id")
						});
						
					$( "<div class=\"menuBox  ui-draggable\"></div>" ).html( ui.draggable.html() ).appendTo( this );
			
				}
			}
		});
	
	$( ".centerMenusArea" ).droppable({
			activeClass: "ui-state-default",
			hoverClass: "ui-state-hover",
			drop: function( event, ui ) {
				var selectedItem =  ui.draggable.find( "div" );
				if(selectedItem.attr("data-type") !== "2"){
					$(ui.draggable).fadeOut();
					$.post("ajax/menus.php?action=move" , {
						"sender" : 1 , 
						"resortPost" : 1 , 
						"align" : 2 , 
						"menuid" : selectedItem.attr("data-id")
						});
						
					$( "<div class=\"menuBox  ui-draggable\"></div>" ).html( ui.draggable.html() ).appendTo( this );
			
				}
			}
		});
		
});
