var height = $('.index-heading').height();
$(window).scroll(function(){
	if($(this).scrollTop() > height){
		$('.categories').addClass('fixed');
	}
	else{
		$('.categories').removeClass('fixed');
	}
});
$(".view-cat").click(function(){
	$(".PC-menu").toggleClass("show-PC");	
	$(".fa-bars").toggle();	
	$(".fa-xmark").toggle();	
});

$(".cat-menu").click(function(){
	$("#cat-mega-menu").toggle();	
	$(".cat-menu").toggleClass("cat-mrgn");	
});
$(".oc-menu").click(function(){
	$("#oc-mega-menu").toggle();	
	$(".oc-menu").toggleClass("oc-mrgn");	
});
/** search script */
$('.serbtnnew').on('click', function() {
    if ($.trim($('#searchKey').val())  === '') {
        $("#searchAlert").css('display','block');
        $("#searchAlert").html("<i class='fa fa-exclamation-triangle'></i> Please Search by flowers, cakes, gifts etc.");
        setTimeout(function(){ 
        $("#searchAlert").html("");
        document.getElementById('searchAlert').style.display = 'none' 
        }, 3000);
        return false;
    }else if($.trim($('#searchKey').val()).length < 3){
        $("#searchAlert").css('display','block');
        $("#searchAlert").html("<i class='fa fa-exclamation-triangle'></i> Please write minimum 3 characters");
        setTimeout(function(){ 
        $("#searchAlert").html("");
        document.getElementById('searchAlert').style.display = 'none' 
        }, 3000);
        return false;
    } else {
        url = "search-keyword.asp?keyword="+$.trim($('#searchKey').val());
        window.location.href = url;
    }
});

$('#searchKey').keypress(function(e){
    if(e.which == 13){
        $('.serbtnnew').click();
    }
});


$(".click-search").click(function(event){
    $("#search-bar_ID").show();
});
$(".search-close").click(function(event){
    $("#search-bar_ID").hide();
    document.getElementById('searchKey').value = "";
});
/**More button opening closing */
$('.faq-btn').click(function(){
    // $('.faq-body').show();
    $(this).addClass("faq-hide")
    $(".faq-body").addClass("flex-show")
    
    });    
    $('.faq-cross').click(function(){
    // $('.faq-body').hide();
    $(".faq-body").removeClass("flex-show")
    $(".faq-btn").removeClass("faq-hide")
});







/**accordion script */
const accordionContent = document.querySelectorAll(".accordion-content");

accordionContent.forEach((item, index) => {
    let header = item.querySelector("header");
    header.addEventListener("click", () =>{
        item.classList.toggle("open");

        let description = item.querySelector(".description");
        if(item.classList.contains("open")){
            description.style.height = `${description.scrollHeight}px`; //scrollHeight property returns the height of an element including padding , but excluding borders, scrollbar or margin
            item.querySelector("i").classList.replace("fa-angle-down", "fa-angle-up");
        }else{
            description.style.height = "0px";
            item.querySelector("i").classList.replace("fa-angle-up", "fa-angle-down");
        }
        removeOpen(index); //calling the funtion and also passing the index number of the clicked header
    })
})

function removeOpen(index1){
    accordionContent.forEach((item2, index2) => {
        if(index1 != index2){
            item2.classList.remove("open");

            let des = item2.querySelector(".description");
            des.style.height = "0px";
            item2.querySelector("i").classList.replace("fa-angle-up", "fa-angle-down");
        }
    })
}

function myFunction() {
  var x = document.getElementById("myDIV");
  if (x.style.display === "none") {
    x.style.display = "block";
  } else {
    x.style.display = "none";
  }
}
// const viewBtn = document.querySelector(".view-modal"),
//     popup = document.querySelector(".popup"),
//     close = popup.querySelector(".close"),
//     field = popup.querySelector(".field"),
//     input = field.querySelector("input"),
//     copy = field.querySelector("button");
//     viewBtn.onclick = ()=>{
//       popup.classList.toggle("show");
//     }
//     close.onclick = ()=>{
//       viewBtn.click();
//     }
//     copy.onclick = ()=>{
//       input.select(); //select input value
//       if(document.execCommand("copy")){ //if the selected text copy
//         field.classList.add("active");
//         copy.innerText = "Copied";
//         setTimeout(()=>{
//           window.getSelection().removeAllRanges(); //remove selection from document
//           field.classList.remove("active");
//           copy.innerText = "Copy";
//         }, 3000);
//       }
//     }




      

     