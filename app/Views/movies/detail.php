<?php
$movie = $movie ?? [];
?>

<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?><?= esc($movie['title']) ?><?= $this->endSection() ?>

<?= $this->section('content') ?>

<?= $this->endSection() ?>
