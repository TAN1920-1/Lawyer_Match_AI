<?php
include_once 'includes/session_init.php';
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" crossorigin="anonymous">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/modern_style.css">
    <title>Get Started - Registration Options</title>
  </head>
  <body>
    <?php include 'includes/navbar.php'; ?>
    <section class="py-5">
      <div class="container">
        <div class="text-center mb-5">
          <h2 class="font-weight-bold">Get Started</h2>
          <p class="text-muted">Choose how you want to join the platform</p>
        </div>
        <div class="row">
          <div class="col-md-4 mb-4">
            <div class="glass-panel p-4 h-100 text-center">
              <div class="mb-3">
                <div class="d-inline-block p-3 rounded-circle bg-light text-primary mb-2">
                  <i class="fas fa-user fa-2x"></i>
                </div>
              </div>
              <h4 class="font-weight-bold mb-2">Register as Client</h4>
              <p class="text-muted">Find and book top lawyers and firms</p>
              <a href="user_register.php" class="btn btn-modern btn-block">Continue</a>
            </div>
          </div>
          <div class="col-md-4 mb-4">
            <div class="glass-panel p-4 h-100 text-center">
              <div class="mb-3">
                <div class="d-inline-block p-3 rounded-circle bg-light text-success mb-2">
                  <i class="fas fa-user-tie fa-2x"></i>
                </div>
              </div>
              <h4 class="font-weight-bold mb-2">Register as Lawyer</h4>
              <p class="text-muted">Join our lawyer network and accept clients</p>
              <a href="lawyer_register.php" class="btn btn-modern btn-block">Continue</a>
            </div>
          </div>
          <div class="col-md-4 mb-4">
            <div class="glass-panel p-4 h-100 text-center">
              <div class="mb-3">
                <div class="d-inline-block p-3 rounded-circle bg-light text-warning mb-2">
                  <i class="fas fa-building fa-2x"></i>
                </div>
              </div>
              <h4 class="font-weight-bold mb-2">Register as Law Firm</h4>
              <p class="text-muted">Create a firm profile and manage team bookings</p>
              <a href="firm_register.php" class="btn btn-modern btn-block">Continue</a>
            </div>
          </div>
        </div>
      </div>
    </section>
    <?php include 'includes/footer.php'; ?>
  </body>
</html>
