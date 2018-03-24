<div>
    <h3>Find Restaurant</h3>
    <?= $this->Form->create() ?>
    <fieldset>
        <?= $this->Form->input('find'); ?>
        <?= $this->Form->button('Submit') ?>
        <?= $this->Form->end() ?>
    </fieldset>
    <table>
    <thead>
        <tr>
            <th>ID</th>
            <th>NAME</th>
            <th>HALAL</th>
            <th>VEGAN</th>
            <th>GLTEN FREE</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($restaurants as $restaurant): ?>
        <tr>
            <td><?= h($restaurant->id) ?></td>
            <td><?= h($restaurant->name) ?></td>
            <td><?= h($restaurant->halal) ?></td>
            <td><?= h($restaurant->vegan) ?></td>
            <td><?= h($restaurant->gluten_free) ?></td>
        </tr>
            <?php endforeach; ?>
            </tbody>
    </table>
</div>
