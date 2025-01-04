// Get the chosen room filled when choosing book now

document.querySelectorAll(".rooms-container .cta").forEach((button) => {
  button.addEventListener("click", (event) => {
    // Get the room id from the button
    const roomId = event.target.getAttribute("data-room-id");

    // Update the booking form
    const roomSelect = document.querySelector(
      'form.booking-form select[name="room_id"]'
    );
    if (roomSelect) {
      roomSelect.value = roomId; //Set the chosen value
    }
  });
});
