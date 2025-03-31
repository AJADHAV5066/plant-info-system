// Add to your existing script
document.addEventListener("DOMContentLoaded", function () {
  // Highlight top prediction
  const predictions = document.querySelectorAll(".prediction-card");
  if (predictions.length > 0) {
    predictions[0].classList.add("border", "border-success", "border-2");
    predictions[0].style.backgroundColor = "rgba(40, 167, 69, 0.1)";
  }

  // Animate confidence bars
  const confidenceBars = document.querySelectorAll(".confidence-level");
  confidenceBars.forEach((bar) => {
    const width = bar.getAttribute("data-confidence") || bar.style.width;
    bar.style.width = "0";
    setTimeout(() => {
      bar.style.width = width + "%";
    }, 100);
  });
});
