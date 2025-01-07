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

// Get the chosen feature pre-filled when choosing an add-on
document.querySelectorAll(".features-container .cta").forEach((button) => {
  button.addEventListener("click", (event) => {
    // Get the feature id from the button
    const featureId = event.target.getAttribute("feature-id");

    // Update the booking form's add-ons
    const addonCheckbox = document.querySelector(
      `form.booking-form input[name="addons[]"][value="${featureId}"]`
    );
    if (addonCheckbox) {
      addonCheckbox.checked = true; // Mark the checkbox as selected
    }
  });
});
