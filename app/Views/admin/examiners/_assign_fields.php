<?php
$selectedAdviserId = $selectedAdviserId ?? '';
$selectedCriticId = $selectedCriticId ?? '';
$selectedPanelIds = $selectedPanelIds ?? [];
$adviserExaminers = $adviserExaminers ?? [];
$criticExaminers = $criticExaminers ?? [];
$panelExaminers = $panelExaminers ?? [];
$showHint = $showHint ?? true;
?>
<div class="col-md-6">
    <label class="form-label small mb-1">Examiner — Adviser</label>
    <select name="examiner_adviser_id" class="form-select form-select-sm examiner-adviser-select">
        <option value="">-- Select registered adviser --</option>
        <?php foreach ($adviserExaminers as $ex): ?>
            <option value="<?= (int) $ex['id'] ?>" <?= (string) $selectedAdviserId === (string) $ex['id'] ? 'selected' : '' ?>><?= esc($ex['name']) ?></option>
        <?php endforeach; ?>
    </select>
</div>
<div class="col-md-6">
    <label class="form-label small mb-1">Examiner — Critic</label>
    <select name="examiner_critic_id" class="form-select form-select-sm examiner-critic-select">
        <option value="">-- Select registered critic --</option>
        <?php foreach ($criticExaminers as $ex): ?>
            <option value="<?= (int) $ex['id'] ?>" <?= (string) $selectedCriticId === (string) $ex['id'] ? 'selected' : '' ?>><?= esc($ex['name']) ?></option>
        <?php endforeach; ?>
    </select>
</div>
<div class="col-12">
    <label class="form-label small mb-1">Examiner — Panel Members</label>
    <select name="examiner_panel_ids[]" class="form-select form-select-sm examiner-panel-select" multiple size="4">
        <?php foreach ($panelExaminers as $ex): ?>
            <option value="<?= (int) $ex['id'] ?>" <?= in_array((int) $ex['id'], array_map('intval', (array) $selectedPanelIds), true) ? 'selected' : '' ?>><?= esc($ex['name']) ?></option>
        <?php endforeach; ?>
    </select>
    <?php if ($showHint): ?>
        <div class="form-text">Only active registered examiners are listed. Hold Ctrl (Windows) or Command (Mac) to select more than one panel member.</div>
    <?php endif; ?>
</div>
