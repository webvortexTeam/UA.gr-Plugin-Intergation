<style>
.vortex-cancel-new {
    z-index: 999 !important;
}

#vortex-ua-info-new-modal {
    overflow-y: auto;
    scroll-behavior: smooth;
}

#vortex-ua-info-new-modal .modal-content {
    max-height: 90vh;
    overflow-y: auto;
    border: 1px solid #ccc;
    background-color: white;
    padding: 20px;
    border-radius: 10px;
}

#vortex-ua-info-new-modal h3 {
    font-size: 1.5em;
    margin-bottom: 15px;
}

#vortex-ua-info-new-modal .new-popup-content {
    color: #333;
    line-height: 1.6;
}

#vortex-ua-info-new-modal .new-popup-content div {
    margin-bottom: 10px;
}

#vortex-ua-info-new-modal .close-btn {
    margin-top: 20px;
    padding: 10px 20px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

#vortex-ua-info-new-modal .close-btn:hover {
    background-color: #0056b3;
}
</style>

<div id="vortex-ua-info-new-modal" class="fixed inset-0 vortex-cancel-new flex items-center justify-center bg-black bg-opacity-50 hidden"  style="z-index: 9999 !important;">
    <div class="modal-content">
         <?php if ($locale_activities === 'en') {
    ?>

<h3 class="text-lg font-semibold mb-4">Frequently Asked Questions</h3>
<a id="close-vortex-ua-info-new-btn" class="mt-4 px-4 py-2 bg-black rounded" style="color: white;">Close</a>

<div class="new-popup-content text-gray-700">
    <div class="col-lg-9">
        <div id="tabPage_1" class="blockbox" data-target-active-submenu-down="tabPage_1">
            <div class="pt-4 pl-4 pr-4">
                <h3 class="mb-2">What is the booking process?</h3>
                <div class="pb-4">
                    <p>Select the desired date, time, number of participants, and additional services (if offered), then proceed to payment through the secure Stripe system. When you make a booking, no charge is made for the reservation amount. The amount is pre-authorized until your booking is confirmed. If the booking is rejected, the amount is automatically released. If the booking is confirmed, only then is the amount charged.</p>
                </div>
                <h3 class="mb-2">How can I pay?</h3>
                <div class="pb-4">
                    <p>We use one of the most secure payment systems from Stripe. You can pay with:</p>
                    <ul>
                        <li>Credit card</li>
                        <li>Debit card</li>
                        <li>Google Pay</li>
                        <li>Apple Pay</li>
                    </ul>
                </div>
                <h3 class="mb-2">How will I know my booking has been made?</h3>
                <div class="pb-4">
                    <p>The booking system supports real-time availability. However, it is up to the partner whether they accept immediate bookings or need to approve them within 48 hours. After completing the booking payment, you will be notified by email whether your booking was successful.</p>
                </div>
                <h3 class="mb-2">I booked an activity. What do I do now?</h3>
                <div class="pb-4">
                    <p>If you have made a booking, you will receive an email with detailed instructions on where and when to be at the venue, along with other useful information. If you did not receive the email, please contact us.</p>
                </div>
                <h3 class="mb-2">Do I need to have the confirmation email printed when I go to the venue?</h3>
                <div class="pb-4">
                    <p>No, it is not necessary. You can show it to our partner in digital form from your mobile phone. However, you do need to show it to them.</p>
                </div>
                <h3 class="mb-2">How can I contact the business where I made a booking?</h3>
                <div class="pb-4">
                    <p>After the booking process, you will receive an email with all the business details. You can also send a message from your account on our platform. It is always recommended to contact the business before the day of your booking.</p>
                </div>
                <h3 class="mb-2">Is it mandatory to contact the business by phone if I have made a booking?</h3>
                <div class="pb-4">
                    <p>No, it is not mandatory. However, for some activities that depend on weather conditions, it is advisable to have contact before you go to the venue. You will have detailed information and advice in the confirmation email.</p>
                </div>
                <h3 class="mb-2">I can't find the confirmation email. What should I do?</h3>
                <div class="pb-4">
                    <p>Make sure you have checked the spam/junk folders of your email. Contact us, and we will resend it to you.</p>
                </div>
            </div>
        </div>
        <div class="gap-50"></div>
        <div id="tabPage_5" class="blockbox" data-target-active-submenu-down="tabPage_5" data-target-active-submenu-up="tabPage_1">
            <div class="blockboxTitle p-3 p-md-4">
                <h2 class="m-0">Cancellations</h2>
            </div>
            <div class="pt-4 pl-4 pr-4">
                <h3 class="mb-2">My booking was confirmed and was canceled by the business. How do I get my money back?</h3>
                <div class="pb-4">
                    <p>The business has the right to cancel a confirmed booking at any time, as they see fit. If for any reason the business canceled your booking, the money will automatically be refunded to your account. If there is a delay in the refund, please contact us.</p>
                </div>
                <h3 class="mb-2">I paid, but my booking was rejected. When will my money be refunded?</h3>
                <div class="pb-4">
                    <p>As mentioned above, since availability constantly changes, a booking is considered confirmed only if you receive the relevant confirmation email. When you make a booking, the amount is pre-authorized, which is automatically released if the booking request is rejected.</p>
                </div>
                <h3 class="mb-2">How do I know my booking was canceled?</h3>
                <div class="pb-4">
                    <p>You will receive an email confirming the cancellation. Check your inbox and spam/junk folders. If you do not receive an email within 24 hours, please contact us to confirm your cancellation.</p>
                </div>
            </div>
        </div>
    </div>
</div>

    <?php
} else {
    ?>
<h3 class="text-lg font-semibold mb-4">Συχνές Ερωτήσεις</h3>
<a id="close-vortex-ua-info-new-btn" class="mt-4 px-4 py-2 bg-black rounded" style="color: white;">Κλείσιμο</a>

<div class="new-popup-content text-gray-700">
    <div class="col-lg-9">
        <div id="tabPage_1" class="blockbox" data-target-active-submenu-down="tabPage_1">
            <div class="pt-4 pl-4 pr-4">
                <h3 class="mb-2">Ποια είναι η διαδικασία κράτησης;</h3>
                <div class="pb-4">
                    <p>Επιλέξτε την επιθυμητή ημερομηνία, την ώρα, τον αριθμό συμμετεχόντων και επιπλέον υπηρεσίες (αν παρέχονται) και προχωρήστε σε πληρωμή μέσω του ασφαλούς συστήματος Stripe. Όταν κάνετε μια κράτηση, δεν γίνεται χρέωση του ποσού της κράτησης. Γίνεται προ-δέσμευση του ποσού μέχρι να επιβεβαιωθεί η κράτηση σας. Αν η κράτηση απορριφθεί γίνεται αυτόματα αποδέσμευση του ποσού σας. Αν η κράτηση επιβεβαιωθεί τότε και μόνο γίνεται χρέωση του ποσού.</p>
                </div>
                <h3 class="mb-2">Πώς μπορώ να πληρώσω;</h3>
                <div class="pb-4">
                    <p>Χρησιμοποιούμε ένα από τα ασφαλέστερα συστήματα πληρωμών της εταιρείας Stripe. Μπορείτε να πληρώσετε με:</p>
                    <ul>
                        <li>Πιστωτική κάρτα</li>
                        <li>Χρεωστική κάρτα</li>
                        <li>Google Pay</li>
                        <li>Apple Pay</li>
                    </ul>
                </div>
                <h3 class="mb-2">Πώς θα ενημερωθώ ότι πραγματοποιήθηκε η κράτησή μου;</h3>
                <div class="pb-4">
                    <p>Το Σύστημα κρατήσεων υποστηρίζει λειτουργία με real time διαθεσιμότητες. Παρ’ όλα αυτά είναι στην κρίση του συνεργάτη εάν θα δέχεται άμεσες κρατήσεις ή θα πρέπει να τις κάνει αποδοχή εντός 48 ωρών. Αφού προβείτε στην πληρωμή της κράτησης θα ενημερωθείτε με email εάν πραγματοποιήθηκε επιτυχώς η κράτησή σας.</p>
                </div>
                <h3 class="mb-2">Έκανα κράτηση μιας δραστηριότητας. Τι κάνω τώρα;</h3>
                <div class="pb-4">
                    <p>Εάν πραγματοποιήσατε μια κράτηση θα λάβετε email με αναλυτικές οδηγίες για το μέρος και την ώρα που θα πρέπει να είστε στην επιχείρηση καθώς επίσης και άλλες χρήσιμες πληροφορίες. Εάν δεν λάβατε το email επικοινωνήστε μαζί μας.</p>
                </div>
                <h3 class="mb-2">Χρειάζεται να έχω εκτυπωμένο το email επιβεβαίωσης όταν πάω στην επιχείρηση;</h3>
                <div class="pb-4">
                    <p>Όχι δεν είναι απαραίτητο. Μπορείτε να το δείξετε στον συνεργάτη μας σε ψηφιακή μορφή από το κινητό σας. Παρ’ όλα αυτά χρειάζεται να του το δείξετε.</p>
                </div>
                <h3 class="mb-2">Πως μπορώ να επικοινωνήσω με την επιχείρηση στην οποία έκανα κράτηση;</h3>
                <div class="pb-4">
                    <p>Μετά την διαδικασία της κράτησης θα λάβετε email με όλα τα στοιχεία της επιχείρησης. Επίσης μπορείτε να στείλετε μήνυμα από τον λογαριασμό σας στην πλατφόρμα μας. Προτείνεται πάντα να επικοινωνείτε με την επιχείρηση πριν την ημέρα της κράτησής σας.</p>
                </div>
                <h3 class="mb-2">Είναι υποχρεωτικό να επικοινωνήσω και τηλεφωνικά εάν έχω κάνει μια κράτηση;</h3>
                <div class="pb-4">
                    <p>Όχι δεν είναι υποχρεωτικό. Σε ορισμένες δραστηριότητες όμως που βασίζονται στις καιρικές συνθήκες καλό θα είναι να έχετε επικοινωνία πριν πάτε στην επιχείρηση. Θα έχετε αναλυτικές πληροφορίες και συμβουλές στο email επιβεβαίωσης.</p>
                </div>
                <h3 class="mb-2">Δε βρίσκω το email επιβεβαίωσης. Τι πρέπει να κάνω;</h3>
                <div class="pb-4">
                    <p>Βεβαιωθείτε ότι έχετε ελέγξει τους φακέλους με τα Ανεπιθύμητα (spam/junk) μηνύματα του email σας. Επικοινωνήστε μαζί μας και θα σας το ξαναστείλουμε.</p>
                </div>
            </div>
        </div>
        <div class="gap-50"></div>
        <div id="tabPage_5" class="blockbox" data-target-active-submenu-down="tabPage_5" data-target-active-submenu-up="tabPage_1">
            <div class="blockboxTitle p-3 p-md-4">
                <h2 class="m-0">Ακυρώσεις</h2>
            </div>
            <div class="pt-4 pl-4 pr-4">
                <h3 class="mb-2">Η κράτηση μου ήταν επιβεβαιωμένη και ακυρώθηκε από την επιχείρηση. Πως παίρνω τα χρήματα μου πίσω;</h3>
                <div class="pb-4">
                    <p>Η επιχείρηση έχει κάθε δικαίωμα να ακυρώσει επιβεβαιωμένη κράτηση οποιαδήποτε χρονική στιγμή όπως αυτή κρίνει. Εάν για οποιοδήποτε λόγο η επιχείρηση ακύρωσε την κράτηση σου, αυτόματα θα επιστραφούν τα χρήματα στον λογαριασμό σου. Εάν έχει καθυστερήσει η επιστροφή επικοινώνησε μαζί μας.</p>
                </div>
                <h3 class="mb-2">Ενώ πλήρωσα η κράτηση μου απορρίφθηκε. Πότε επιστρέφονται τα χρήματα;</h3>
                <div class="pb-4">
                    <p>Όπως αναφέραμε παραπάνω, καθώς οι διαθεσιμότητες μεταβάλλονται συνεχώς, μια κράτηση θεωρείται επιβεβαιωμένη μόνο αν λάβετε το σχετικό email επιβεβαίωσης. Όταν κάνετε κράτηση, γίνεται μια προ-δέσμευση του ποσού, το οποίο αποδεσμεύεται αυτόματα αν απορριφθεί το αίτημα κράτησης.</p>
                </div>
                <h3 class="mb-2">Πως ξέρω ότι ακυρώθηκε η κράτηση μου;</h3>
                <div class="pb-4">
                    <p>Θα λάβετε ένα email που επιβεβαιώνει την ακύρωση. Ελέγξτε τους φακέλους με τα Εισερχόμενα και τα Ανεπιθύμητα (spam/junk) μηνύματα του email σας. Αν δε λάβετε email σε 24 ώρες, παρακαλούμε επικοινωνήστε μαζί μας για να επιβεβαιώσετε την ακύρωσή σας.</p>
                </div>
            </div>
        </div>
    </div>
</div>




    <?php } ?>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const newPopupBtn = document.getElementById('vortex-ua-info-new-btn');
    const newPopupModal = document.getElementById('vortex-ua-info-new-modal');
    const closeNewPopupBtn = document.getElementById('close-vortex-ua-info-new-btn');

    newPopupBtn.addEventListener('click', function () {
        newPopupModal.classList.remove('hidden');
    });

    closeNewPopupBtn.addEventListener('click', function () {
        newPopupModal.classList.add('hidden');
    });

    // Optional: Close the modal when clicking outside of it
    newPopupModal.addEventListener('click', function (event) {
        if (event.target === newPopupModal) {
            newPopupModal.classList.add('hidden');
        }
    });
});
</script>