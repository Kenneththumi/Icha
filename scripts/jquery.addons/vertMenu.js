// JavaScript Document
	$(function(){
	 $(".vertMenu").buildMenu(
      {
        menuWidth:206,
        openOnRight:false,
        menuSelector: ".usermenuContainer",
        iconPath:"ico/",
        hasImages:false,
        fadeInTime:200,
        fadeOutTime:200,
        adjustLeft:0,
        adjustTop:0,
        opacity:1.0,
        openOnClick:false,
        minZindex:200,
        shadow:false,
		shadowColor:"transparent",
        hoverIntent:300,
        submenuHoverIntent:300,
        closeOnMouseOut:true
      });
	});