<?php
    $genre = $genre ?? [];
?>

<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?><?= esc($genre['name']) ?><?= $this->endSection() ?>

<?= $this->section('content') ?>

<?= $this->endSection() ?>
