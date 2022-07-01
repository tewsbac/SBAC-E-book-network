$(function(){
	//แสดงจำนวนสินค้าบนเมนู
	upDateNavCartNumber();
	//แสดงตะกร้าสินค้า
	$.ajax({
  		url:'include/miniCartAjax.php',
  		type:'get',
  		success:function(data){
  			$("#cart-content-mini").empty().append(data).fadeIn(1000);
  		}
  	});
});
//คลิกเพจลิงค์ด้านล่าง 
$(document).on('click','.ajax-page-link',function() {
		var currentTagId = $(this).attr('id');
		var str = currentTagId;
		var n = str.indexOf("-");
		var page = str.slice(n+1);
		pageNo = parseInt(page);
     	myProductAjax(pageNo);
});
//คลิกเมนูติดต่อเรา
$(document).on('click','#contact-us-nav',function() {
	var n = true;
	$.ajax({
  		url:'contactUsAjax.php',
  		data:{link:n},
  		type:'get',
  		success:function(data){
  			$("#product-container").empty().append(data).fadeIn(1000);
  			$("#category-container").hide();
  			$("#lastest-link-content").hide();
  		}
  	});	
});

$(document).on('click','.ajax-page-link-product-list',function() {
		var currentTagId = $(this).attr('id');
		var str = currentTagId;
		var n = str.indexOf("-");
		var page = str.slice(n+1);
		pageNo = parseInt(page);
		var currentTagCatId = $('.hidden-cat-product').attr('id');	
		var strCatId = currentTagCatId;
		var m = strCatId.indexOf("-");
		var cat = strCatId.slice(m+1);
		catId = parseInt(cat);
     	listProductAjax(pageNo,catId);
});
//คลิก paging link ที่ผลลัพธ์มาจาก Search
$(document).on('click','.ajax-page-link-search-list',function() {
		var currentTagId = $(this).attr('id');
		var str = currentTagId;
		var n = str.indexOf("-");
		var page = str.slice(n+1);
		pageNo = parseInt(page);
		var currentSearchStr = $('.hidden-search-product').attr('id');	
		var strSearch = currentSearchStr;
		var m = strSearch.indexOf("-");
		var searchText = strSearch.slice(m+1);
     	productSearchAjax(pageNo,searchText);
});

$(document).on('click','.add-to-cart',function() {
		var currentId = $(this).attr('id');
		var str = currentId;
		var n = str.indexOf("-");
		var id = str.slice(n+1);
		productId = parseInt(id);
		miniCartAjax(productId);
});

$(document).on('click','.show-detail-product',function() {
		var currentId = $(this).attr('id');
		var str = currentId;
		var n = str.indexOf("-");
		var id = str.slice(n+1);
		productId = parseInt(id);
		var currentTagCatId = $('.hidden-cat-product').attr('id');	
		var strCatId = currentTagCatId;
		var m = strCatId.indexOf("-");
		var cat = strCatId.slice(m+1);
		catId = parseInt(cat);
		showProductDetailAjax(productId,catId);

});

$(document).on('click','.list-group-item',function() {
		var currentId = $(this).attr('id');
		var str = currentId;
		var n = str.indexOf("-");
		var id = str.slice(n+1);
		catId = parseInt(id);		
		listProductAjax(1,catId);
});

$(document).on('click','.home-link',function() {
		var currentId = $(this).attr('id');
		var str = currentId;
		var n = str.indexOf("-");
		var id = str.slice(n+1);
		catId = parseInt(id);
		listProductAjax(1,catId);
});

$(document).on('click','.category-group-item',function() {
		var currentId = $(this).attr('id');
		var str = currentId;
		var n = str.indexOf("-");
		var id = str.slice(n+1);
		catId = parseInt(id);
		listProductAjax(1,catId);
});

$(document).on('click','.search-product',function() {
	$(window).scrollTop($('#product-container').offset().top);
	var str = $('#search-box').val();
	var findString = str.replace(/([ #;&,.%+*~\':"!^$[\]()=>|\/])/g,'\\\\$1');
	productSearchAjax(1,findString);

});

$(document).on('click','#category-button-switch',function() {
	var classUp = $(this).hasClass('category-switch-up');
	if(classUp == true){
		$( "#category-content-switch" ).slideUp( "slow", function() {
    		$("#category-button-switch").toggleClass('category-switch-up');
    		$("#switch-up-down-name").empty().append('&nbsp;&nbsp;&nbsp;แสดงประเภทสินค้า&nbsp;&nbsp;&nbsp;');
    		$("#category-direction-icon").removeClass('glyphicon-chevron-up');
    		$("#category-direction-icon").addClass('glyphicon-chevron-down');
  		});
  	} else {
  		$( "#category-content-switch" ).slideDown( "slow", function() {
    		$("#category-button-switch").toggleClass('category-switch-up');
    		$("#switch-up-down-name").empty().append('&nbsp;&nbsp;&nbsp;ซ่อนประเภทสินค้า&nbsp;&nbsp;&nbsp;');
    		$("#category-direction-icon").removeClass('glyphicon-chevron-down');
    		$("#category-direction-icon").addClass('glyphicon-chevron-up');
  		});
  	}
});

function productSearchAjax(n,str){
	$("#product-container").empty().append('<div align="center"><br><img src="images/loader.gif" id="category-loading"><br><br></div>');
  	$.ajax({
  		url:'include/productSearchAjax.php',
  		data:{page:n,strSearch:str},
  		type:'get',
  		success:function(data){
  			setTimeout(function(){
  				$("#product-container").empty().append(data).fadeIn(1000);
  				searchPagingLinkAjax(n,str);
  			}, 500);
  		}
  	});
}

function searchPagingLinkAjax(n,str){
  	$.ajax({
  		url:'include/searchPagingLinkAjax.php',
  		data:{page:n,strSearch:str},
  		type:'get',
  		success:function(data){
  			$("#lastest-link-content").empty().append(data).fadeIn(1000);
  		}
  	});
}

function myProductAjax(n){
	$("#product-container").empty().append('<div align="center"><br><img src="images/loader.gif" id="category-loading"><br><br></div>');
  	$.ajax({
  		url:'include/lastestProductWithAjax.php',
  		data:{page:n},
  		type:'get',
  		success:function(data){
  			setTimeout(function(){
  				$("#product-container").empty().append(data).fadeIn(1000);
  				$("#category-container").show();
  				pagingLinkAjax(n);
  			}, 500);
  		}
  	});

}

function pagingLinkAjax(n){
  	$.ajax({
  		url:'include/lastestPagingLinkAjax.php',
  		data:{page:n},
  		type:'get',
  		success:function(data){
  			$("#lastest-link-content").empty().append(data).fadeIn(1000);
  		}
  	});
}

function listProductAjax(n,catId){
	$("#product-container").empty().append('<div align="center"><br><img src="images/loader.gif" id="category-loading"><br><br></div>');
  	$.ajax({
  		url:'include/listProductWithAjax.php',
  		data:{page:n,c:catId},
  		type:'get',
  		success:function(data){  		
  			setTimeout(function(){
  				$("#product-container").empty().append(data).fadeIn(1000);
  				$("#category-container").show();
  				listPagingLinkAjax(n,catId);
  			}, 500);
  		}
  	});
}
function listPagingLinkAjax(m,cat){
  	$.ajax({
  		url:'include/listProductPagingLinkAjax.php',
  		data:{page:m,c:cat},
  		type:'get',
  		success:function(data){
  			$("#lastest-link-content").empty().append(data).fadeIn(1000);
  		}
  	});
}



function miniCartAjax(productId){
  	$.ajax({
  		url:'include/miniCartAjax.php',	//ร้องขอไปยังไฟล์ miniCartAjax.php
  		data:{productAddTominCart:productId},	//ส่งรหัสสินค้าไป
  		type:'get',			//เลือก type เป็นแบบ get
  		success:function(data){
			//แสดงตะกร้าสินค้าที่ด้านขวามือของเว็บเพจ 
  			$("#cart-content-mini").empty().append(data).fadeIn(1000);
  			//อัพเดทจำนวนของในตะกร้าที่เมนู
  			upDateNavCartNumber();
  			$('html, body').animate({
        		scrollTop: $("#cart-content-mini").offset().top-10
    		}, 1000);
    		
  		}
  	});
}

function upDateNavCartNumber(){
  	$.ajax({
  		url:'include/navCartNumAjax.php',
  		type:'get',
  		success:function(data){
  			$("#nav-cart-number").empty().append(data).fadeIn(1000);
  		}
  	});
}

function showProductDetailAjax(productId,catId){
	productId = parseInt(productId);
	$("#product-container").empty().append('<div align="center"><br><img src="images/loader.gif" id="category-loading"><br><br></div>');
  	$.ajax({
  		url:'include/productDetailAjax.php',
  		data:{pdId:productId,cat:catId},
  		type:'get',
  		success:function(data){
  			$("#product-container").empty().append(data).fadeIn(1000);
  			$("#lastest-link-content").empty();
  			$("#category-container").hide();
    		$(window).scrollTop($('#product-container').offset().top);
  		}
  	});
}

function submitSearchByEnter(e)
{
	var keycode;
	if (window.event) {
		keycode = window.event.keyCode;
	} else if (e) {
		keycode = e.which;
	} else {
		return true;
	}
	if (keycode == 13){
   		$( "#search-product-button" ).trigger( "click" );
   		return false;
   	} else {
   		return true;
   	}
}