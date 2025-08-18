// Reserve Equipment Modal Logic
function openReservePopup(equipmentId, equipmentName, status) {
  if (status !== 'available') return;
  // Fetch available slots via AJAX
  fetch(`/project/student/get_slots.php?equipment_id=${equipmentId}`)
    .then(response => response.json())
    .then(slots => {
      let today = new Date();
      let maxDate = new Date();
      maxDate.setDate(today.getDate() + 7);
      let minDateStr = today.toISOString().split('T')[0];
      let maxDateStr = maxDate.toISOString().split('T')[0];
      let slotOptions = slots.map(slot => `<option value='${slot}'>${slot}</option>`).join('');
      var popup = document.createElement('div');
      popup.id = 'reserve-popup';
      popup.className = 'modal-overlay fade-in';
      popup.innerHTML = `
        <div class='modal-content'>
          <h3>Reserve Equipment – ${equipmentName}</h3>
          <form id='reserveForm'>
            <input type='hidden' name='equipment_id' value='${equipmentId}'>
            <label>Slot Time:</label><br>
            <select name='slot_time' required>${slotOptions}</select><br><br>
            <label>Return Date:</label><br>
            <input type='date' name='return_date' required min='${minDateStr}' max='${maxDateStr}'><br><br>
            <label>Availability Status:</label>
            <input type='text' value='${status}' readonly><br><br>
            <div id='modal-error' style='color:red;'></div>
            <button class='btn' type='submit'>Confirm Booking</button>
            <button class='btn' type='button' onclick='closeReservePopup()'>Cancel</button>
          </form>
        </div>
      `;
      document.body.appendChild(popup);
      document.getElementById('reserveForm').onsubmit = function(e) {
        e.preventDefault();
        let formData = new FormData(this);
        fetch('/project/student/reserve_equipment.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            alert(data.message);
            closeReservePopup();
            // Update availability status in real time
            document.getElementById('status-' + equipmentId).textContent = 'reserved';
          } else {
            document.getElementById('modal-error').textContent = data.message;
          }
        });
      };
    });
}
function closeReservePopup() {
  var popup = document.getElementById('reserve-popup');
  if (popup) {
    popup.classList.remove('fade-in');
    popup.classList.add('fade-out');
    setTimeout(() => popup.remove(), 300);
  }
}
