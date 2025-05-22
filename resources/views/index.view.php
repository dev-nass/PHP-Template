<?php require base_path('resources/views/components/head.php') ?>

<section>
    <form action="index" method="POST">
        <div>
            <label for="email">Email</label>
            <input 
                type="email"
                name="email"
                id="email" value="<?= old('email') ?>">
                <?php error('email') ?>
        </div>
        <div>
            <label for="password">Password</label>
            <input 
                type="password"
                name="password"
                id="password">
                <?php error('password') ?>
        </div>
        <div>
            <label for="password_confirmation">Password Confirmation</label>
            <input 
                type="password"
                name="password_confirmation"
                id="password_confirmation">
                <?php error('password_confirmation') ?>
        </div>
        <button>Submit Form</button>
    </form>
</section>

<?php require base_path('resources/views/components/foot.php') ?>