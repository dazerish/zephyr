
<section class="user-container">
    <h1 class="page-title"><b>Borrow These Devices</b></h1>
    <div class="borrow_module">
            <div class="device-view">
                <div class="device-card">
                    <h4 class="device-title">Keyboard</h4>
                    <ul class="dev-specs">
                        <li>Unique Number</li>
                        <li>Model</li>
                        <li>Manufacturer</li>
                    </ul>
                </div>

                <div class="device-card">
                    <h4 class="device-title">Mouse</h4>
                    <ul class="dev-specs">
                        <li>Unique Number</li>
                        <li>Model</li>
                        <li>Manufacturer</li>
                    </ul>
                </div>

                <div class="device-card">
                    <h4 class="device-title">Monitor</h4>
                    <ul class="dev-specs">
                        <li>Unique Number</li>
                        <li>Model</li>
                        <li>Manufacturer</li>
                    </ul>
                </div>
            </div>





        <div class="picker-flex">
            <div class="picker-div">

                    <input type="hidden" name="dev-name" value="">
                    <input type="hidden" name="unique-num" value="">
              
                <input type="hidden" name="borrower" value="">

                <label for="reason" class="register_label">Reason</label><br>
                <textarea rows="1" cols="50" wrap="physical" id="reason" name="reason"></textarea><br>
                <span class="text-danger"></span>

                <label for="reservation-date">Pick a reservation date:</label><br>
                <input type="date" id="reservation_date" class="date-picker" name="reservation_date">
                <span class="text-danger"></span>  

                <div class="btn-grp">
                    <button type="submit" class="cancel-btn" name="cancel-button">Cancel</button>
                    <button type="submit" class="reserve-btn" name="borrow-device">Borrow Device</button>
                </div>
            </div>
        </div>

    </div>

</section>


