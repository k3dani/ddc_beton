document.addEventListener("DOMContentLoaded", function () {
    const vatRate = 0.27; // VAT rate of 27%

    // Retrieve quantity from local storage
    const result = parseFloat(localStorage.getItem("quantity"));
    if (isNaN(result) || result <= 0) {
        console.error("Invalid quantity in local storage");
        return;
    }

    // Append necessary elements to DOM
    const kalkContainer = document.querySelector("#kalk-cia");
    if (!kalkContainer) {
        console.error("#kalk-cia container not found");
        return;
    }

    const appendElements = `
        <div class="custom-form-siurbliai">
            <label>Select PUMI:</label>
            <select id="pumiSelect">
                <option value="none">No PUMI needed</option>
            </select>
            <label class="filable" style="display:none;">
                <span>Pump hours needed at object:</span>
                <input type="number" id="pumpHours" value="0">
            </label>
        </div>
    `;
    kalkContainer.innerHTML += appendElements;

    // Retrieve pick-up point data from local storage
    const pickupPointData = JSON.parse(localStorage.getItem("pickupPointData"));
    if (!pickupPointData || !pickupPointData.pumis) {
        console.error("Invalid or missing pickupPointData in local storage");
        return;
    }

    const pumiSelect = document.getElementById("pumiSelect");
    if (!pumiSelect) {
        console.error("#pumiSelect element not found");
        return;
    }

    // Populate the PUMI select input with options
    pickupPointData.pumis.forEach(pumi => {
        const option = document.createElement("option");
        option.value = pumi.name; // Use the name or an identifier as the value
        option.dataset.price = pumi.price; // Add the price as a data attribute
        option.dataset.fixedPrice = pumi.fixed_price; // Add the fixed price as a data attribute
        option.text = `${pumi.name}`;
        pumiSelect.appendChild(option);
    });
    // Event listeners for inputs
    document.getElementById("pumpHours")?.addEventListener("input", debounce(calculateTotalPrice, 500));
    pumiSelect.addEventListener("change", () => {
        const selectedOption = pumiSelect.options[pumiSelect.selectedIndex];
        const filable = document.querySelector(".filable");
        if (!selectedOption || selectedOption.value === "none") {
            filable.style.display = "none";
        } else {
            filable.style.display = "block";
        }
        calculateTotalPrice();
    });

    function calculateTotalPrice() {
        console.log("Calculating total price...");

        if (!pickupPointData) {
            console.error("pickupPointData is missing, cannot calculate price");
            return;
        }

        const selectedOption = pumiSelect.options[pumiSelect.selectedIndex];
        const pumpPricePerHour = selectedOption?.dataset.price ? parseFloat(selectedOption.dataset.price) : 0;
        const pumpFixedPrice = selectedOption?.dataset.fixedPrice ? parseFloat(selectedOption.dataset.fixedPrice) : 0;
        const pumpHours = parseFloat(document.getElementById("pumpHours")?.value) || 0;

        const pumpNet = pumpFixedPrice + pumpPricePerHour * pumpHours; // Add fixed price to hourly price calculation
        const pumpGross = pumpNet * (1 + vatRate);

        const transportationNet = pickupPointData.price * result;
        const transportationGross = transportationNet * (1 + vatRate);

        console.log({
            transportationNet,
            transportationGross,
            pumpNet,
            pumpGross,
        });

        // Save calculated data to localStorage
        const productId = 248; // Replace this with your dynamic product ID
        const calculatedData = {
            transportation: {
                gross: transportationGross,
                net: transportationNet,
            },
            pump: {
                gross: pumpGross,
                net: pumpNet,
            },
        };
        localStorage.setItem(`calculated_data_${productId}`, JSON.stringify(calculatedData));

        updateBookingFormTotals(transportationGross, transportationNet, pumpGross, pumpNet);
        sendUpdatedPriceToServer(transportationGross, transportationNet, pumpGross, pumpNet, productId);

        if (observer) observer.disconnect();
        if (observer) observer.observe(targetNode, { childList: true, subtree: true });
    }

    function updateBookingFormTotals(transportationGross, transportationNet, pumpGross, pumpNet) {
        const totalsContainer = document.querySelector(".yith-wcbk-booking-form-totals");
        if (!totalsContainer) {
            console.error(".yith-wcbk-booking-form-totals container not found");
            return;
        }

        totalsContainer.innerHTML = `
            <div class="transportation">
                <div class="label">Transportation price:</div>
                <div class="numbers">
                    <div class="gross">Gross: ${transportationGross.toFixed()} Ft</div>
                    <div class="net">Net: ${transportationNet.toFixed()} Ft</div>
                </div>
            </div>
            <div class="pump">
                <div class="label">Pump price:</div>
                <div class="numbers">
                    <div class="gross">Gross: ${pumpGross.toFixed()} Ft</div>
                    <div class="net">Net: ${pumpNet.toFixed()} Ft</div>
                </div>
            </div>`;
    }

    function sendUpdatedPriceToServer(transportationGross, transportationNet, pumpGross, pumpNet, productId) {
        console.log("Sending updated prices to server...");
        jQuery.post("/wp-admin/admin-ajax.php", {
            action: "update_product_price",
            transportation_gross: transportationGross,
            transportation_net: transportationNet,
            pump_gross: pumpGross,
            pump_net: pumpNet,
            product_id: productId,
        })
            .done(response => {
                if (response.success) {
                    console.log("Prices updated successfully:", response);
                } else {
                    console.error("Failed to update prices:", response.data);
                }
            })
            .fail(() => console.error("AJAX request failed"));
    }

    const targetNode = document.querySelector(".yith-wcbk-booking-form-totals");
    let observer;

    if (targetNode) {
        observer = new MutationObserver((mutationsList) => {
            for (const mutation of mutationsList) {
                if (mutation.type === "childList" || mutation.type === "subtree") {
                    console.log("Detected changes in totals, recalculating...");
                    calculateTotalPrice();
                }
            }
        });

        observer.observe(targetNode, { childList: true, subtree: true });
    } else {
        console.error(".yith-wcbk-booking-form-totals container not found for observation.");
    }

    // Debounce function to limit excessive function calls
    function debounce(func, wait) {
        let timeout;
        return function (...args) {
            const context = this;
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(context, args), wait);
        };
    }

});
