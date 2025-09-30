<?php
include_once("navbar.php");

if (isset($_SESSION['usertype']) && ($_SESSION['usertype'] === '1' || $_SESSION['usertype'] == 2)) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>public/css/invoice.css">
    <title>Invoice - <?php echo $_SESSION['team_username']; ?></title>
    <style>
        
    </style>
</head>
<body>
    <div class="invoicelist">
        <h5>
        <?php 
        if($_SESSION['usertype'] == 2){
            echo $_SESSION['team_username']." - ";
        } ?>Invoice List</h5>

        <table border="1" cellpadding="6" cellspacing="0">
            <tr>
                <th>SL</th>
                <?php if($_SESSION['usertype'] === '1'){?>
                <th>Team Name</th>
                <?php } ?>
                <th>Total Hours</th>
                <th>Total Amount</th>
                <th>Submit Date</th>
                <th>Status</th>
                <?php if($_SESSION['usertype'] === '1'){?>
                <th>Action</th>
                <?php } ?>
            </tr>

            <?php
            $invoiceData = getallinvoiceteam($conn);
            if ($invoiceData && count($invoiceData) > 0) {
                $sl = 1;
                foreach ($invoiceData as $invoice) {
                    $teamid = $invoice['team_id'];
                    // get team name
                    $sql = "SELECT full_name FROM user_team WHERE user_id='$teamid' LIMIT 1";
                    $result = mysqli_query($conn, $sql);
                    $teamname = "";
                    if ($result && mysqli_num_rows($result) > 0) {
                        $row = mysqli_fetch_assoc($result);
                        $teamname = $row['full_name'];
                    }

                    echo "<tr>";
                    echo "<td>{$sl}</td>";

                    // Conditionally show team name
                    if ($_SESSION['usertype'] === '1') {
                        echo "<td>{$teamname}</td>";
                    }
                    
                    $amount = $invoice['total'] * 22.5;
                    echo "<td>{$invoice['total']}</td>
                          <td>$ {$amount}</td>
                          <td>{$invoice['submitdate']}</td>";
                    echo "<td>";
                    {
                        // Team Member: show status text
                        if ($invoice['status'] == 1) {
                            echo "<span style='color: red; font-weight:600;'>Pending</span>";
                        } elseif ($invoice['status'] == 2) {
                            echo "<span style='color: orange; font-weight:600;'>Approved</span>";
                        } elseif ($invoice['status'] == 3) {
                            echo "<span style='color: green; font-weight:600;'>Paid</span><br>
                                  <small>TxID: {$invoice['txid']}</small>";
                        } else {
                            echo "<span style='color: red; font-weight:600;'>Declined</span>";
                        }
                    }
                    echo "</td>";
                    // Status Column
                    
                    ?>
                    <form method="post" action="<?php echo $base_url;?>controller/taskController.php">
                        <input type="hidden" name="invoice_id" value="<?php echo $invoice['sl']; ?>">
                    <?php
                    if ($_SESSION['usertype'] == 1) {
                        echo "<td>";
                        // Admin: show action buttons
                        echo "<div class='status-actions' data-invoiceid='{$invoice['status']}'>
                                <button type='submit' class='approveBtn' name='approve_invoice' style='background-color:green;'>Approve</button>
                                <button class='paidBtn'  style='background-color:#234f1E;'>Paid</button>
                                <div class='txid-input'>
                                    <input type='text' placeholder='Enter TxID' name='txid'>
                                    <button type='submit' class='saveTxidBtn'  name='paid_invoice'>Save</button>
                                </div>
                                <button type='submit' class='rejectBtn' name='reject_invoice' style='background-color:red;'>Reject</button>
                                
                              </div>";
                        echo "</td>";
                    } 
                    
                    ?>
                    </form>
                    <?php

                    echo "</tr>";
                    $sl++;
                }
            } else {
                echo "<tr><td colspan='6'>No invoice data available.</td></tr>";
            }
            ?>
        </table>

        <br>
        <?php if ($_SESSION['usertype'] == 2) { ?>
        <button id="addInvoiceBtn" class="addinvoicebtn">Add Invoice</button>
        <?php } ?>
    </div>

    <!-- Overlay -->
    <div class="overlay" id="overlay"></div>

    <!-- Hidden Add Invoice Form -->
    <div id="addInvoiceForm" class="form-container">
        <input type="hidden" name="team_id" value="<?php echo $_SESSION['team_id']; ?>">
        <h3>Add Weekly Hours</h3>
        <form method="post" action="">
            <label>Monday Hours:</label>
            <input type="number" name="monday" min="0" step="0.1" class="workhour" required>

            <label>Tuesday Hours:</label>
            <input type="number" name="tuesday" min="0" step="0.1" class="workhour" required>

            <label>Wednesday Hours:</label>
            <input type="number" name="wednesday" min="0" step="0.1" class="workhour" required>

            <label>Thursday Hours:</label>
            <input type="number" name="thursday" min="0" step="0.1" class="workhour" required>

            <label>Friday Hours:</label>
            <input type="number" name="friday" min="0" step="0.1" class="workhour" required>

            <div class="total-display">
                Total Hours: <span id="totalHours">0</span>
            </div>

            <input type="hidden" name="total" id="totalInput">

            <br>
            <button type="submit" class="addinvoicebtn" style='background-color: green;' name="submit_invoice">Save</button>
            <button type="button" class="addinvoicebtn"style='background-color: red;'  id="closeForm">Cancel</button>
        </form>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", () => {
        const addBtn = document.getElementById("addInvoiceBtn");
        const formDiv = document.getElementById("addInvoiceForm");
        const closeBtn = document.getElementById("closeForm");
        const overlay = document.getElementById("overlay");
        const workhourInputs = document.querySelectorAll(".workhour");
        const totalDisplay = document.getElementById("totalHours");
        const totalInput = document.getElementById("totalInput");

        if (addBtn) {
            addBtn.addEventListener("click", () => {
                formDiv.style.display = "block";
                overlay.style.display = "block";
            });
        }

        if (closeBtn) {
            closeBtn.addEventListener("click", () => {
                formDiv.style.display = "none";
                overlay.style.display = "none";
            });
        }

        if (overlay) {
            overlay.addEventListener("click", () => {
                formDiv.style.display = "none";
                overlay.style.display = "none";
            });
        }

        // Auto-calc total hours
        workhourInputs.forEach(input => {
            input.addEventListener("input", () => {
                let total = 0;
                workhourInputs.forEach(i => {
                    total += parseFloat(i.value) || 0;
                });
                totalDisplay.textContent = total;
                totalInput.value = total;
            });
        });

        // Admin Status Action Buttons
        document.querySelectorAll(".approveBtn").forEach(btn => {
            btn.addEventListener("click", () => {
                alert("Approved clicked (implement backend)");
            });
        });

        document.querySelectorAll(".rejectBtn").forEach(btn => {
            btn.addEventListener("click", () => {
                alert("Rejected clicked (implement backend)");
            });
        });

        document.querySelectorAll(".paidBtn").forEach(btn => {
            btn.addEventListener("click", (e) => {
                const container = e.target.closest(".status-actions");
                const txidDiv = container.querySelector(".txid-input");
                txidDiv.classList.add("show");
            });
        });

        document.querySelectorAll(".saveTxidBtn").forEach(btn => {
            btn.addEventListener("click", (e) => {
                const container = e.target.closest(".status-actions");
                const txid = container.querySelector("input").value;
                alert("Save TxID: " + txid + " (implement backend)");
            });
        });
    });
    </script>
</body>
</html>
<?php
} else {
    header("Location: " . $base_url . "public/views/team_dashboard/team_dashboard.php");
}
?>
