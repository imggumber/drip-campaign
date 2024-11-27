// Function to convert date to New York time
function setNewYorkTime() {
    // Create a new date object in UTC time
    const now = new Date();

    // Convert the current UTC time to New York time (Eastern Time Zone)
    const newYorkTime = new Date(now.toLocaleString("en-US", {
        timeZone: "America/New_York"
    }));

    // Format the date to match the "datetime-local" input format (YYYY-MM-DDTHH:mm)
    const formattedDate = newYorkTime.toISOString().slice(0, 16);

    // Set the value of the datetime-local input using jQuery
    $("#datetime-local").val(formattedDate);
}

$(function () {
    setNewYorkTime();
});
