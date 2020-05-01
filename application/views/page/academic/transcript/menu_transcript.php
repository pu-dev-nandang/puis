
<div class="tabbable tabbable-custom tabbable-full-width">
    <ul class="nav nav-tabs">
        <li class="<?= ($this->uri->segment(3)=='' ||
                        $this->uri->segment(3)=='setting-transcript') ? 'active' : ''; ?>">
            <a href="<?= base_url('academic/transcript'); ?>">Transcript</a>
        </li>
        <li class="hide <?= ($this->uri->segment(3)=='ijazah') ? 'active' : ''; ?>">
            <a href="<?= base_url('academic/transcript/ijazah'); ?>">Ijazah</a>
        </li>
    </ul>
    <div style="border-top: 1px solid #cccccc">

        <div class="row">
            <div class="col-md-12">
                <?= $page; ?>
            </div>
        </div>

    </div>
</div>