$(document).ready(function () {
    $('.profile-dropdown').hide();
    $('#profile_avatar').click(function () {
        manipulateDropdown();
    });

});


function manipulateDropdown() {
    $('.profile-dropdown').slideToggle();
}