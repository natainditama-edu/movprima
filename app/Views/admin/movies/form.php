<?php
    $movie = $movie ?? [];
?>

<?= $this->extend('layouts/admin') ?>

<?= $this->section('title') ?><?= $movie ? 'Edit Movie' : 'Add Movie' ?><?= $this->endSection() ?>

<?= $this->section('content') ?>

<?= $this->endSection() ?>
