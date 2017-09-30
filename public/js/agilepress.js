// this code is being used for touch testing on mobile devices
/*
jQuery(function(){
  jQuery("div.w3-card-4").bind("taphold", tapholdHandler);

  function tapholdHandler(event){
    jQuery(event.target).addClass("notewrinkled");
  }
});
*/

// refreshes the boards every 45 seconds
/*
jQuery(document).ready(function(){
	setInterval(function(){cache_clear()},90000);

	var column = document.getElementById("scrumboard");
	if (!empty(column)) {
		column.style.height = sizeColumns();
	}

});
*/
function cache_clear() {
	window.location.reload(true);
}

// this is the code needed for dragging and dropping notes
function allowDrop(ev) {
    ev.preventDefault();
}

function drag(ev) {
    ev.dataTransfer.setData("text", ev.target.id);
	//localStorage.setItem("source", ev.view);
}

function drop(ev) {
    ev.preventDefault();

	// var source = localStorage.getItem("source");
	// localStorage.removeItem("source");

    var item = ev.dataTransfer.getData("text");
    ev.target.appendChild(document.getElementById(item));

	if (isNaN(ev.target.id)) {
		new_status = ev.target.id;
	} else {
		new_status = ev.currentTarget.id;
	}

    jQuery.ajax({
      type: 'POST',
      url : myAjax.ajaxurl,
      data : {
          action : 'moveitem_ajax',
          id : item,
          status : new_status// ev.target.id,
		  //priority : my_priority,
		  //source : source
      },
      success : function(response) {
		 window.location.reload(true);
     }
    });
}

// on right-click on a note, go to view screen
function mouseaction(name, action) {
    action.preventDefault();

    var getUrl = window.location;
    var baseUrl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1];

    window.location = baseUrl + "/?agilepress-tasks=" + name;
}

// who knew testing to see if something was empty would be such a deal!
/* Thanks to https://www.sitepoint.com/testing-for-empty-values/ for the code! */
function empty(data)
{
  if (typeof(data) == 'number' || typeof(data) == 'boolean') {
    return false;
  }
  if (typeof(data) == 'undefined' || data === null) {
    return true;
  }
  if (typeof(data.length) != 'undefined') {
    return data.length == 0;
  }
  var count = 0;
  for (var i in data) {
    if (data.hasOwnProperty(i)) {
      count ++;
    }
  }
  return count == 0;
}

// I don't think this is being used
function alignment(left, right) {
    if (left.checked) {
        return "left";
    } else {
        return "right";
    }
}

// Icons at bottom of notes
function noticons(id, action) {
	var window_id = 'id-' + action + '-' + id;

	document.getElementById(window_id).style.display='block';

}

//jQuery(window).load(function(){
jQuery(document).ready(function(){
	var epicCol = document.getElementById("isepic");
	var storyCol = document.getElementById("isstory");

	/*
	if (jQuery(epicCol).height() > jQuery(storyCol).height()) {
		jQuery(storyCol, this).height(jQuery(epicCol).height());
	} else {
		jQuery(storyCol, this).height(jQuery(epicCol).height());
	}
	*/


      // Cache the highest
      var highestBox = 0;

      // Select and loop the elements you want to equalise
      jQuery(".ap-column", this).each(function(){

        // If this box is higher than the cached highest then store it
        if(jQuery(this).height() > highestBox) {
          highestBox = jQuery(this).height();
        }

      });

      // Set the height of all those children to whichever was highest
      jQuery(".ap-column", this).height(highestBox);


});

function addButtonOpen(board, name, excerpt) {
	document.getElementById('ap-add-item').style.display='block';
}

function addButtonClose(board, name, excerpt) {
	document.getElementById('ap-add-item').style.display='none';
}
