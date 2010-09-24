<?php
    $javascript->link('jquery/jquery', false);
?>

<?php echo $form->create('User'); ?>
<fieldset>
    <legend><?php echo __('Add to your profile?', true); ?> <span class="small">(<?php echo __('optional', true); ?>)</span></legend>
    <?php
        echo $form->input('full_name', array('label' => __('Full Name', true)));
        echo $form->input('location', array('label' => __('Location', true))); 
        echo $form->input('country_id', array('label' => __('Country', true), 'empty' => __('Please select', true)));
        echo $form->input('postcode', array('label' => __('Postal Code', true)));
        echo $form->input('gender_id', array('label' => __('Gender', true), 'empty' => __('Please select', true)));
    ?>
        <div class="submit">
        <?php
            echo $form->submit(__('Save Changes', true), array('div' => false));
            echo $form->button(__('Skip this step...', true), array('type' => 'button', 'id' => 'skip'));
        ?>
        </div>
    </form>
    <script type="text/javascript">
        $('#skip').click(function() { 
            window.location.href = '<?php echo $html->url(array('action'=>'skip')); ?>';
        });
    </script>
</fieldset>
