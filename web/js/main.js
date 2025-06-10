/**
 * Main JavaScript functions for the inventory system
 */

// Declare the $ variable before using it
const $ = window.jQuery

// Add confirmation for delete actions
$(document).on("click", ".btn-danger[data-confirm]", function (e) {
  if (!confirm($(this).data("confirm"))) {
    e.preventDefault()
    return false
  }
  return true
})

// Initialize tooltips
$(() => {
  $('[data-toggle="tooltip"]').tooltip()
})

// Handle low stock alerts
$(document).ready(() => {
  // Check if there are low stock warnings
  var lowStockCount = $(".low-stock-count").data("count")

  if (lowStockCount > 0) {
    // Show notification
    var notification = $('<div class="alert alert-warning alert-dismissible fade show" role="alert">')
      .append("<strong>¡Alerta de Inventario Bajo!</strong> Hay " + lowStockCount + " productos con inventario bajo.")
      .append(
        '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>',
      )

    $("#notification-container").append(notification)
  }
})

// Print function
function printElement(elementId) {
  var printContents = document.getElementById(elementId).innerHTML
  var originalContents = document.body.innerHTML

  document.body.innerHTML = printContents
  window.print()
  document.body.innerHTML = originalContents
}
