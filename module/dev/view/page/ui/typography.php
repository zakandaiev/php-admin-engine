<?php Theme::template('ui-header', ['title' => 'Typography']); ?>

<div class="mt-2">
  <div class="row cols-xs-1 cols-md-2 gap-xs">

    <div class="col">
      <div class="row fill gap-xs cols-xs-1">

        <div class="col">
          <div class="box">
            <div class="box__header">
              <h5 class="box__title">Headings</h5>
              <h6 class="box__subtitle">All HTML headings, <code>&lt;h1&gt;</code> through <code>&lt;h6&gt;</code>, are available.</h6>
            </div>

            <div class="box__body">
              <h1>This is a heading h1</h1>
              <p class="text-muted">Etiam rhoncus. Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet
                adipiscing sem neque sed ipsum. Nam quam nunc, blandit vel, luctus pulvinar, hendrerit id, lorem. Maecenas nec odio et
                ante tincidunt tempus. Donec vitae sapien ut libero.</p>
              <h2>This is a heading h2</h2>
              <p class="text-muted">Etiam rhoncus. Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet
                adipiscing sem neque sed ipsum. Nam quam nunc, blandit vel, luctus pulvinar, hendrerit id, lorem.</p>
              <h3>This is a heading h3</h3>
              <p class="text-muted">Etiam rhoncus. Maecenas tempus, tellus condimentum rhoncus, sem quam libero, sit amet adipiscing sem
                neque sed ipsum. Nam quam nunc, vel.</p>
              <h4>This is a heading h4</h4>
              <p class="text-muted">Etiam rhoncus. Maecenas tempus, tellus condimentum rhoncus, sem quam libero, sit amet adipiscing sem
                neque sed ipsum. Nam quam nunc, vel.</p>
              <h5>This is a heading h5</h5>
              <p class="text-muted">Sit amet adipiscing sem neque sed ipsum. Nam quam nunc, blandit vel.</p>
              <h6>This is a heading h6</h6>
              <p class="text-muted">Sit amet adipiscing sem neque sed ipsum. Nam quam nunc, blandit vel.</p>
            </div>
          </div>
        </div>

        <div class="col">
          <div class="box">
            <div class="box__header">
              <h5 class="box__title">Coloured text</h5>
              <h6 class="box__subtitle text-muted">Contextual text classes.</h6>
            </div>
            <div class="box__body">
              <p class="color-primary">This line of text contains the text-primary class.</p>
              <p class="color-secondary">This line of text contains the text-secondary class.</p>
              <p class="color-success">This line of text contains the text-success class.</p>
              <p class="color-danger">This line of text contains the text-danger class.</p>
              <p class="color-warning">This line of text contains the text-warning class.</p>
              <p class="color-info">This line of text contains the text-info class.</p>
            </div>
          </div>
        </div>

      </div>
    </div>

    <div class="col">
      <div class="row fill gap-xs cols-xs-1">

        <div class="col">
          <div class="box">
            <div class="box__header">
              <h5 class="box__title">Inline text</h5>
              <h6 class="box__subtitle text-muted">Styling for common inline HTML5 elements.</h6>
            </div>
            <div class="box__body">
              <p>You can use the mark-tag to <mark>highlight</mark> text.</p>
              <p><del>This line of text can be treated as deleted text.</del></p>
              <p><ins>This line of text can be treated as an addition to the document.</ins></p>
              <p><strong>Bold text using the strong-tag</strong></p>
              <p><em>Italicized text using the em-tag</em></p>
            </div>
          </div>
        </div>

        <div class="col">
          <div class="box">
            <div class="box__header">
              <h5 class="box__title">Blockquote</h5>
            </div>
            <div class="box__body">
              <blockquote>
                <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit.</p>
                <p>Quibusdam neque repellat adipisci omnis corrupti?</p>
                <p>Ut fugiat alias vero aliquid officia voluptatem cupiditate.</p>
              </blockquote>
            </div>
          </div>
        </div>

        <div class="col">
          <div class="box">
            <div class="box__header">
              <h5 class="box__title">List unordered</h5>
              <h6 class="box__subtitle text-muted">The unordered list items will are marked with bullets.</h6>
            </div>
            <div class="box__body">
              <ul>
                <li>
                  Lorem ipsum dolor sit amet
                </li>
                <li>
                  Consectetur adipiscing elit
                </li>
                <li>
                  Nulla volutpat aliquam velit
                </li>
                <li>
                  Phasellus iaculis neque
                </li>
                <li>
                  Eget porttitor lorem
                </li>
              </ul>
            </div>
          </div>
        </div>

        <div class="col">
          <div class="box">
            <div class="box__header">
              <h5 class="box__title">List ordered</h5>
              <h6 class="box__subtitle text-muted">The ordered list items will are marked with numbers.</h6>
            </div>
            <div class="box__body">
              <ol>
                <li>
                  Lorem ipsum dolor sit amet
                </li>
                <li>
                  Consectetur adipiscing elit
                </li>
                <li>
                  Nulla volutpat aliquam velit
                </li>
                <li>
                  Phasellus iaculis neque
                </li>
                <li>
                  Eget porttitor lorem
                </li>
              </ol>
            </div>
          </div>
        </div>

      </div>
    </div>

  </div>
</div>

<?php Theme::template('ui-footer'); ?>
