<?php Theme::template('ui-header', ['title' => 'Tables']); ?>

<div class="mt-2">
  <div class="row fill cols-xs-1 cols-md-2 gap-xs">

    <div class="col">
      <div class="box">
        <div class="box__header">
          <h4 class="box__title">Basic table</h4>
        </div>
        <div class="box__body">

          <table class="table">
            <thead>
              <tr>
                <th>Name</th>
                <th>Phone Number</th>
                <th>Date of Birth</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Vanessa Tucker</td>
                <td>864-348-0485</td>
                <td>June 21, 1961</td>
                <td class="table__actions">
                  <a href="#" class="table__action" data-tooltip="top" title="View"><i class="ti ti-eye"></i></a>
                  <a href="#" class="table__action" data-tooltip="top" title="Edit"><i class="ti ti-edit"></i></a>
                  <button type="button" class="table__action" data-tooltip="top" title="Delete"><i class="ti ti-trash"></i></button>
                </td>
              </tr>
              <tr>
                <td>William Harris</td>
                <td>914-939-2458</td>
                <td>May 15, 1948</td>
                <td class="table__actions">
                  <a href="#" class="table__action" data-tooltip="top" title="View"><i class="ti ti-eye"></i></a>
                  <a href="#" class="table__action" data-tooltip="top" title="Edit"><i class="ti ti-edit"></i></a>
                  <button type="button" class="table__action" data-tooltip="top" title="Delete"><i class="ti ti-trash"></i></button>
                </td>
              </tr>
              <tr>
                <td>Sharon Lessman</td>
                <td>704-993-5435</td>
                <td>September 14, 1965</td>
                <td class="table__actions">
                  <a href="#" class="table__action" data-tooltip="top" title="View"><i class="ti ti-eye"></i></a>
                  <a href="#" class="table__action" data-tooltip="top" title="Edit"><i class="ti ti-edit"></i></a>
                  <button type="button" class="table__action" data-tooltip="top" title="Delete"><i class="ti ti-trash"></i></button>
                </td>
              </tr>
              <tr>
                <td>Christina Mason</td>
                <td>765-382-8195</td>
                <td>April 2, 1971</td>
                <td class="table__actions">
                  <a href="#" class="table__action" data-tooltip="top" title="View"><i class="ti ti-eye"></i></a>
                  <a href="#" class="table__action" data-tooltip="top" title="Edit"><i class="ti ti-edit"></i></a>
                  <button type="button" class="table__action" data-tooltip="top" title="Delete"><i class="ti ti-trash"></i></button>
                </td>
              </tr>
              <tr>
                <td>Robin Schneiders</td>
                <td>202-672-1407</td>
                <td>October 12, 1966</td>
                <td class="table__actions">
                  <a href="#" class="table__action" data-tooltip="top" title="View"><i class="ti ti-eye"></i></a>
                  <a href="#" class="table__action" data-tooltip="top" title="Edit"><i class="ti ti-edit"></i></a>
                  <button type="button" class="table__action" data-tooltip="top" title="Delete"><i class="ti ti-trash"></i></button>
                </td>
              </tr>
            </tbody>
          </table>

        </div>
      </div>
    </div>

    <div class="col">
      <div class="box">
        <div class="box__header">
          <h4 class="box__title">Striped table</h4>
        </div>
        <div class="box__body">

          <table class="table table_striped">
            <thead>
              <tr>
                <th>Name</th>
                <th>Phone Number</th>
                <th>Date of Birth</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Vanessa Tucker</td>
                <td>864-348-0485</td>
                <td>June 21, 1961</td>
                <td class="table__actions">
                  <a href="#" class="table__action" data-tooltip="top" title="View"><i class="ti ti-eye"></i></a>
                  <a href="#" class="table__action" data-tooltip="top" title="Edit"><i class="ti ti-edit"></i></a>
                  <button type="button" class="table__action" data-tooltip="top" title="Delete"><i class="ti ti-trash"></i></button>
                </td>
              </tr>
              <tr>
                <td>William Harris</td>
                <td>914-939-2458</td>
                <td>May 15, 1948</td>
                <td class="table__actions">
                  <a href="#" class="table__action" data-tooltip="top" title="View"><i class="ti ti-eye"></i></a>
                  <a href="#" class="table__action" data-tooltip="top" title="Edit"><i class="ti ti-edit"></i></a>
                  <button type="button" class="table__action" data-tooltip="top" title="Delete"><i class="ti ti-trash"></i></button>
                </td>
              </tr>
              <tr>
                <td>Sharon Lessman</td>
                <td>704-993-5435</td>
                <td>September 14, 1965</td>
                <td class="table__actions">
                  <a href="#" class="table__action" data-tooltip="top" title="View"><i class="ti ti-eye"></i></a>
                  <a href="#" class="table__action" data-tooltip="top" title="Edit"><i class="ti ti-edit"></i></a>
                  <button type="button" class="table__action" data-tooltip="top" title="Delete"><i class="ti ti-trash"></i></button>
                </td>
              </tr>
              <tr>
                <td>Christina Mason</td>
                <td>765-382-8195</td>
                <td>April 2, 1971</td>
                <td class="table__actions">
                  <a href="#" class="table__action" data-tooltip="top" title="View"><i class="ti ti-eye"></i></a>
                  <a href="#" class="table__action" data-tooltip="top" title="Edit"><i class="ti ti-edit"></i></a>
                  <button type="button" class="table__action" data-tooltip="top" title="Delete"><i class="ti ti-trash"></i></button>
                </td>
              </tr>
              <tr>
                <td>Robin Schneiders</td>
                <td>202-672-1407</td>
                <td>October 12, 1966</td>
                <td class="table__actions">
                  <a href="#" class="table__action" data-tooltip="top" title="View"><i class="ti ti-eye"></i></a>
                  <a href="#" class="table__action" data-tooltip="top" title="Edit"><i class="ti ti-edit"></i></a>
                  <button type="button" class="table__action" data-tooltip="top" title="Delete"><i class="ti ti-trash"></i></button>
                </td>
              </tr>
            </tbody>
          </table>

        </div>
      </div>
    </div>

    <div class="col">
      <div class="box">
        <div class="box__header">
          <h4 class="box__title">Small basic table</h4>
        </div>
        <div class="box__body">

          <table class="table table_sm">
            <thead>
              <tr>
                <th>Name</th>
                <th>Phone Number</th>
                <th>Date of Birth</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Vanessa Tucker</td>
                <td>864-348-0485</td>
                <td>June 21, 1961</td>
                <td class="table__actions">
                  <a href="#" class="table__action" data-tooltip="top" title="View"><i class="ti ti-eye"></i></a>
                  <a href="#" class="table__action" data-tooltip="top" title="Edit"><i class="ti ti-edit"></i></a>
                  <button type="button" class="table__action" data-tooltip="top" title="Delete"><i class="ti ti-trash"></i></button>
                </td>
              </tr>
              <tr>
                <td>William Harris</td>
                <td>914-939-2458</td>
                <td>May 15, 1948</td>
                <td class="table__actions">
                  <a href="#" class="table__action" data-tooltip="top" title="View"><i class="ti ti-eye"></i></a>
                  <a href="#" class="table__action" data-tooltip="top" title="Edit"><i class="ti ti-edit"></i></a>
                  <button type="button" class="table__action" data-tooltip="top" title="Delete"><i class="ti ti-trash"></i></button>
                </td>
              </tr>
              <tr>
                <td>Sharon Lessman</td>
                <td>704-993-5435</td>
                <td>September 14, 1965</td>
                <td class="table__actions">
                  <a href="#" class="table__action" data-tooltip="top" title="View"><i class="ti ti-eye"></i></a>
                  <a href="#" class="table__action" data-tooltip="top" title="Edit"><i class="ti ti-edit"></i></a>
                  <button type="button" class="table__action" data-tooltip="top" title="Delete"><i class="ti ti-trash"></i></button>
                </td>
              </tr>
              <tr>
                <td>Christina Mason</td>
                <td>765-382-8195</td>
                <td>April 2, 1971</td>
                <td class="table__actions">
                  <a href="#" class="table__action" data-tooltip="top" title="View"><i class="ti ti-eye"></i></a>
                  <a href="#" class="table__action" data-tooltip="top" title="Edit"><i class="ti ti-edit"></i></a>
                  <button type="button" class="table__action" data-tooltip="top" title="Delete"><i class="ti ti-trash"></i></button>
                </td>
              </tr>
              <tr>
                <td>Robin Schneiders</td>
                <td>202-672-1407</td>
                <td>October 12, 1966</td>
                <td class="table__actions">
                  <a href="#" class="table__action" data-tooltip="top" title="View"><i class="ti ti-eye"></i></a>
                  <a href="#" class="table__action" data-tooltip="top" title="Edit"><i class="ti ti-edit"></i></a>
                  <button type="button" class="table__action" data-tooltip="top" title="Delete"><i class="ti ti-trash"></i></button>
                </td>
              </tr>
            </tbody>
          </table>

        </div>
      </div>
    </div>

    <div class="col">
      <div class="box">
        <div class="box__header">
          <h4 class="box__title">Small striped table</h4>
        </div>
        <div class="box__body">

          <table class="table table_sm table_striped">
            <thead>
              <tr>
                <th>Name</th>
                <th>Phone Number</th>
                <th>Date of Birth</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Vanessa Tucker</td>
                <td>864-348-0485</td>
                <td>June 21, 1961</td>
                <td class="table__actions">
                  <a href="#" class="table__action" data-tooltip="top" title="View"><i class="ti ti-eye"></i></a>
                  <a href="#" class="table__action" data-tooltip="top" title="Edit"><i class="ti ti-edit"></i></a>
                  <button type="button" class="table__action" data-tooltip="top" title="Delete"><i class="ti ti-trash"></i></button>
                </td>
              </tr>
              <tr>
                <td>William Harris</td>
                <td>914-939-2458</td>
                <td>May 15, 1948</td>
                <td class="table__actions">
                  <a href="#" class="table__action" data-tooltip="top" title="View"><i class="ti ti-eye"></i></a>
                  <a href="#" class="table__action" data-tooltip="top" title="Edit"><i class="ti ti-edit"></i></a>
                  <button type="button" class="table__action" data-tooltip="top" title="Delete"><i class="ti ti-trash"></i></button>
                </td>
              </tr>
              <tr>
                <td>Sharon Lessman</td>
                <td>704-993-5435</td>
                <td>September 14, 1965</td>
                <td class="table__actions">
                  <a href="#" class="table__action" data-tooltip="top" title="View"><i class="ti ti-eye"></i></a>
                  <a href="#" class="table__action" data-tooltip="top" title="Edit"><i class="ti ti-edit"></i></a>
                  <button type="button" class="table__action" data-tooltip="top" title="Delete"><i class="ti ti-trash"></i></button>
                </td>
              </tr>
              <tr>
                <td>Christina Mason</td>
                <td>765-382-8195</td>
                <td>April 2, 1971</td>
                <td class="table__actions">
                  <a href="#" class="table__action" data-tooltip="top" title="View"><i class="ti ti-eye"></i></a>
                  <a href="#" class="table__action" data-tooltip="top" title="Edit"><i class="ti ti-edit"></i></a>
                  <button type="button" class="table__action" data-tooltip="top" title="Delete"><i class="ti ti-trash"></i></button>
                </td>
              </tr>
              <tr>
                <td>Robin Schneiders</td>
                <td>202-672-1407</td>
                <td>October 12, 1966</td>
                <td class="table__actions">
                  <a href="#" class="table__action" data-tooltip="top" title="View"><i class="ti ti-eye"></i></a>
                  <a href="#" class="table__action" data-tooltip="top" title="Edit"><i class="ti ti-edit"></i></a>
                  <button type="button" class="table__action" data-tooltip="top" title="Delete"><i class="ti ti-trash"></i></button>
                </td>
              </tr>
            </tbody>
          </table>

        </div>
      </div>
    </div>

    <div class="col">
      <div class="box">
        <div class="box__header">
          <h4 class="box__title">Borderless table</h4>
        </div>
        <div class="box__body">

          <table class="table table_borderless">
            <thead>
              <tr>
                <th>Name</th>
                <th>Phone Number</th>
                <th>Date of Birth</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Vanessa Tucker</td>
                <td>864-348-0485</td>
                <td>June 21, 1961</td>
                <td class="text-right">
                  <a href="#" class="btn btn_sm btn_outline btn_cancel">View</a>
                </td>
              </tr>
              <tr>
                <td>William Harris</td>
                <td>914-939-2458</td>
                <td>May 15, 1948</td>
                <td class="text-right">
                  <a href="#" class="btn btn_sm btn_outline btn_cancel">View</a>
                </td>
              </tr>
              <tr>
                <td>Sharon Lessman</td>
                <td>704-993-5435</td>
                <td>September 14, 1965</td>
                <td class="text-right">
                  <a href="#" class="btn btn_sm btn_outline btn_cancel">View</a>
                </td>
              </tr>
            </tbody>
          </table>

        </div>
      </div>
    </div>

    <div class="col">
      <div class="box">
        <div class="box__header">
          <h4 class="box__title">Small borderless table</h4>
        </div>
        <div class="box__body">

          <table class="table table_sm table_borderless">
            <thead>
              <tr>
                <th>Name</th>
                <th>Phone Number</th>
                <th>Date of Birth</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Vanessa Tucker</td>
                <td>864-348-0485</td>
                <td>June 21, 1961</td>
                <td class="text-right">
                  <a href="#" class="btn btn_sm btn_outline btn_cancel">View</a>
                </td>
              </tr>
              <tr>
                <td>William Harris</td>
                <td>914-939-2458</td>
                <td>May 15, 1948</td>
                <td class="text-right">
                  <a href="#" class="btn btn_sm btn_outline btn_cancel">View</a>
                </td>
              </tr>
              <tr>
                <td>Sharon Lessman</td>
                <td>704-993-5435</td>
                <td>September 14, 1965</td>
                <td class="text-right">
                  <a href="#" class="btn btn_sm btn_outline btn_cancel">View</a>
                </td>
              </tr>
            </tbody>
          </table>

        </div>
      </div>
    </div>

    <div class="col">
      <div class="box">
        <div class="box__header">
          <h5 class="box__title">Table responsive</h5>
        </div>
        <div class="box__body">
          <div class="table-responsive">
            <table class="table mb-0">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Heading</th>
                  <th scope="col">Heading</th>
                  <th scope="col">Heading</th>
                  <th scope="col">Heading</th>
                  <th scope="col">Heading</th>
                  <th scope="col">Heading</th>
                  <th scope="col">Heading</th>
                  <th scope="col">Heading</th>
                  <th scope="col">Heading</th>
                  <th scope="col">Heading</th>
                  <th scope="col">Heading</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <th scope="row">1</th>
                  <td>Cell</td>
                  <td>Cell</td>
                  <td>Cell</td>
                  <td>Cell</td>
                  <td>Cell</td>
                  <td>Cell</td>
                  <td>Cell</td>
                  <td>Cell</td>
                  <td>Cell</td>
                  <td>Cell</td>
                  <td>Cell</td>
                </tr>
                <tr>
                  <th scope="row">2</th>
                  <td>Cell</td>
                  <td>Cell</td>
                  <td>Cell</td>
                  <td>Cell</td>
                  <td>Cell</td>
                  <td>Cell</td>
                  <td>Cell</td>
                  <td>Cell</td>
                  <td>Cell</td>
                  <td>Cell</td>
                  <td>Cell</td>
                </tr>
                <tr>
                  <th scope="row">3</th>
                  <td>Cell</td>
                  <td>Cell</td>
                  <td>Cell</td>
                  <td>Cell</td>
                  <td>Cell</td>
                  <td>Cell</td>
                  <td>Cell</td>
                  <td>Cell</td>
                  <td>Cell</td>
                  <td>Cell</td>
                  <td>Cell</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>

<?php Theme::template('ui-footer'); ?>
