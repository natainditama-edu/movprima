<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="min-h-screen flex items-center justify-center">

    <div class="card w-96 bg-base-100 shadow-xl">
        <div class="card-body">

            <h2 class="card-title">
                CI4 + DaisyUI 🚀
            </h2>

            <p>
                Modern dashboard vibes ala shadcn.
            </p>

            <button class="btn btn-primary">
                Login
            </button>

        </div>
    </div>

</div>

<?= $this->endSection() ?>