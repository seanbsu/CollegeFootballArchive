<?php
include_once 'nav.php';
?>
    <body>

    <div class="center-container">
        <div class="contact-form-container">
            <div class="contact-form">
                <h1>Contact Us</h1>

                <div class="contact-us-container">
                    <form action="process_form.php" method="POST">
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" placeholder="Your name..">

                        <label for="subject">Subject:</label>
                        <input type="text" id="subject" name="subject" placeholder="Subject">

                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" placeholder="Email">

                        <label for="message">Message:</label>
                        <textarea id="message" name="message" placeholder="Write something.." style="height:200px"></textarea>

                        <input type="submit" value="Submit">
                    </form>
                </div>
            </div>
        </div>
    </div>

    </body>

<?php
include_once 'footer.php';
