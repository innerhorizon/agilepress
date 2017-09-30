/*
jQuery(document).ready( function () {
    jQuery('#scrum-product-info').DataTable();
} );

jQuery(document).ready( function () {
    jQuery('#scrum-sprint-info').DataTable();
} );

jQuery(document).ready( function () {
    jQuery('#scrum-task-info').DataTable();
} );

jQuery(document).ready( function () {
    jQuery('#scrum-story-info').DataTable();
} );
*/
function openTab(evt, tabName) {
    // Declare all variables
    var i, tabcontent, tablinks;

    // Get all elements with class="tabcontent" and hide them
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    // Get all elements with class="tablinks" and remove the class "active"
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    // Show the current tab, and add an "active" class to the button that opened the tab
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " active";
}

function wizOnClick(step, name, info, excerpt) {
    // Check browser support
    if (typeof(Storage) !== "undefined") {
        if (step != '5') {
            // Store
            sessionStorage.setItem(step + "name", name);
            sessionStorage.setItem(step + "info", info);
            sessionStorage.setItem(step + "excerpt", excerpt);
        }
    } else {
        window.alert("Upgrade your browser, you ninny!");
    }
}

function wizOnFinish(productID, storyID, taskID, sprintID) {
    // Load existing posts
    var xhttp = new XMLHttpRequest();
    xhttp.open("GET", "Your Rest URL Here", false);
    xhttp.setRequestHeader("Content-type", "application/json");
    xhttp.send();
    var response = JSON.parse(xhttp.responseText);

    var productPost = new wp.api.models.Post( { id: productID } );
    productPost.fetch();

    var storyPost = new wp.api.models.Post( { id: storyID } );
    storyPost.fetch();

    var taskPost = new wp.api.models.Post( { id: taskID } );
    taskPost.fetch();

    var sprintPost = new wp.api.models.Post( { id: sprintID } );
    sprintPost.fetch();

    // add the pages
    var storyboardPage = new wp.api.models.Page( { title: 'This is a test page one' } );
    storyboardPage.save();

    var backlogPage = new wp.api.models.Page( { title: 'This is a test page two' } );
    backlogPage.save();

    var sprintboardPage = new wp.api.models.Page( { title: 'This is a test page three' } );
    sprintboardPage.save();

    //todo fix URL so host isn't hardcoded
    /*
    jQuery.ajax({
        async: false,
        url: 'http://localhost/wordpress/wp-json/wp/v2/pages',
        type: 'POST',
        beforeSend: function(xhr) {
            xhr.setRequestHeader('X-WP-Nonce', wpApiSettings.nonce);
        },
        data: {
            title: document.getElementById("product-name").innerHTML = sessionStorage.getItem("1name"),
            content: document.getElementById("product-info").innerHTML = sessionStorage.getItem("1info"),
            excerpt: document.getElementById("product-excerpt").innerHTML = sessionStorage.getItem("1excerpt"),
            status: "publish"
        },
        success: function(response) {
            productID = response.id;
        },
        complete:  function(response) {
            productID = response.id;
        }
    });

    window.alert(productID);
    */

    /*
        // Retrieve Product
        document.getElementById("product-name").innerHTML = sessionStorage.getItem("1name");
        document.getElementById("product-info").innerHTML = sessionStorage.getItem("1info");
        document.getElementById("product-excerpt").innerHTML = sessionStorage.getItem("1excerpt");
        // Retrieve Story
        document.getElementById("story-name").innerHTML = sessionStorage.getItem("2name");
        document.getElementById("story-info").innerHTML = sessionStorage.getItem("2info");
        document.getElementById("story-excerpt").innerHTML = sessionStorage.getItem("2excerpt");
        // Retrieve Task
        document.getElementById("task-name").innerHTML = sessionStorage.getItem("3name");
        document.getElementById("task-info").innerHTML = sessionStorage.getItem("3info");
        document.getElementById("task-excerpt").innerHTML = sessionStorage.getItem("3excerpt");
        // Retrieve Sprint
        document.getElementById("sprint-name").innerHTML = sessionStorage.getItem("4name");
        document.getElementById("sprint-info").innerHTML = sessionStorage.getItem("4info");
        document.getElementById("sprint-excerpt").innerHTML = sessionStorage.getItem("4excerpt");
        */

}

function reportSelect(product) {
    window.alert(product);
}
