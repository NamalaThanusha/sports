// AJAX for real-time equipment availability
function checkAvailability(equipmentId, callback) {
  var xhr = new XMLHttpRequest();
  xhr.open('GET', '/project/student/search.php?equipment_id=' + equipmentId, true);
  xhr.onload = function() {
    if (xhr.status === 200) {
      callback(JSON.parse(xhr.responseText));
    }
  };
  xhr.send();
}
