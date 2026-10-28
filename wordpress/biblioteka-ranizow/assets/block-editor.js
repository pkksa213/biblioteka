(function (wp) {
  'use strict';
  const el = wp.element.createElement;
  const Panel = wp.editor.PluginDocumentSettingPanel || wp.editPost.PluginDocumentSettingPanel;
  const { SelectControl, TextControl, Notice } = wp.components;
  function localTime(timestamp) { return timestamp ? wp.date.date('Y-m-d\\TH:i', Number(timestamp) * 1000) : ''; }
  function DateField(props) {
    const [value, setValue] = wp.element.useState(localTime(props.value));
    wp.element.useEffect(() => setValue(localTime(props.value)), [props.value]);
    return el(TextControl, { id: props.id, type: 'datetime-local', label: props.label, value,
      onChange: next => {
        setValue(next);
        const parsed = next ? wp.date.getDate(next).getTime() : 0;
        props.onChange(Number.isFinite(parsed) && parsed > 0 ? Math.floor(parsed / 1000) : 0);
      }
    });
  }
  function LibraryFields() {
    const meta = wp.data.useSelect(select => select('core/editor').getEditedPostAttribute('meta') || [], []);
    const { editPost } = wp.data.useDispatch('core/editor');
    const change = (key, value) => editPost({ meta: { ...meta, [key]: value } });
    const kind = meta._br_kind || 'article';
    const children = [el(SelectControl, { key: 'kind', id: 'br-kind', label: 'Rodzaj treści', value: kind, options: [
      { label: 'Artykuł', value: 'article' }, { label: 'Nowa książka', value: 'book' },
      { label: 'Wydarzenie', value: 'event' }, { label: 'Ważne ogłoszenie', value: 'notice' }
    ], onChange: value => change('_br_kind', value) })];
    if (kind === 'book') children.push(
      el(TextControl, { key: 'author', id: 'br_book_author', label: 'Autor książki', value: meta._br_book_author || '', onChange: value => change('_br_book_author', value) }),
      el(TextControl, { key: 'catalog', id: 'br_book_catalog', type: 'url', label: 'Link do książki w katalogu', help: 'Opcjonalnie. Wklej pełny adres zaczynający się od https://.', value: meta._br_book_catalog || '', onChange: value => change('_br_book_catalog', value) })
    );
    if (kind === 'event') children.push(
      el(DateField, { key: 'start', id: 'br_event_start', label: 'Data i godzina rozpoczęcia', value: meta._br_event_start, onChange: value => change('_br_event_start', value) }),
      el(DateField, { key: 'end', id: 'br_event_end', label: 'Data i godzina zakończenia (opcjonalnie)', value: meta._br_event_until, onChange: value => change('_br_event_until', value) }),
      el(TextControl, { key: 'place', id: 'br_event_place', label: 'Miejsce wydarzenia', value: meta._br_event_place || '', onChange: value => change('_br_event_place', value) }),
      el('p', { key: 'timezone' }, 'Czas według strefy ustawionej w WordPressie. Bez zakończenia wydarzenie będzie widoczne do końca dnia.'),
      !meta._br_event_start ? el(Notice, { key: 'required', status: 'warning', isDismissible: false }, 'Uzupełnij termin przed publikacją wydarzenia.') : null
    );
    if (kind === 'notice') children.push(
      el(DateField, { key: 'expiry', id: 'br_notice_until', label: 'Wyświetlaj ogłoszenie do (opcjonalnie)', value: meta._br_notice_until, onChange: value => change('_br_notice_until', value) }),
      el('p', { key: 'expiry-help' }, 'Puste pole oznacza brak terminu wygaśnięcia. Ogłoszenie pojawi się po opublikowaniu wpisu.')
    );
    children.push(el('p', { key: 'image' }, 'Opis wpisz w edytorze, a okładkę, zdjęcie lub plakat ustaw jako obrazek wyróżniający.'));
    return el(Panel, { name: 'br-content', title: 'Treść biblioteki', className: 'br-native-panel' }, ...children);
  }
  wp.plugins.registerPlugin('br-library-content', { render: LibraryFields });
})(window.wp);
