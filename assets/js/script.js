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
// document.querySelectorAll(".features-container .cta").forEach((button) => {
//   button.addEventListener("click", (event) => {
//     // Get the feature id from the button
//     const featureId = event.target.getAttribute("feature-id");

//     // Update the booking form's add-ons
//     const addonCheckbox = document.querySelector(
//       `form.booking-form input[name="addons[]"][value="${featureId}"]`
//     );
//     if (addonCheckbox) {
//       addonCheckbox.checked = true; // Mark the checkbox as selected
//     }
//   });
// });

//Script that calculates the total cost and shows it to the user before doing a booking
document.addEventListener("DOMContentLoaded", () => {
  const roomSelect = document.querySelector('select[name="room_id"]');
  const addonsCheckboxes = document.querySelectorAll('input[name="addons[]"]');
  const arrivalDateInput = document.querySelector('input[name="arrival_date"]');
  const departureDateInput = document.querySelector(
    'input[name="departure_date"]'
  );
  const totalCostElement = document.getElementById("total-cost");

  function calculateTotalCost() {
    let totalCost = 0;

    // Add room price
    const selectedRoomId = roomSelect.value;
    if (selectedRoomId) {
      totalCost += roomPrices[selectedRoomId];
    }

    // Calculate the number of nights
    const arrivalDate = new Date(arrivalDateInput.value);
    const departureDate = new Date(departureDateInput.value);
    if (!isNaN(arrivalDate) && !isNaN(departureDate)) {
      const diffTime = departureDate - arrivalDate;
      const nights = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
      if (nights > 0) {
        totalCost *= nights;
      }
    }

    // Add price for chosen add-ons (only once per booking)
    addonsCheckboxes.forEach((checkbox) => {
      if (checkbox.checked) {
        totalCost += addonPrices[checkbox.value];
      }
    });

    // Show the total cost
    totalCostElement.textContent = totalCost.toFixed(2);
  }

  // Add event listeners that look for changes made
  roomSelect.addEventListener("change", calculateTotalCost);
  addonsCheckboxes.forEach((checkbox) => {
    checkbox.addEventListener("change", calculateTotalCost);
  });
  arrivalDateInput.addEventListener("change", calculateTotalCost);
  departureDateInput.addEventListener("change", calculateTotalCost);
});
