/* global EasyMDE, wpDocsifyEditor */
( function () {
	'use strict';

	const cfg        = wpDocsifyEditor;
	const i18n       = cfg.i18n;
	let mde          = null;
	let currentPath  = null;
	let isDirty      = false;
	let selectedFolder = null;

	// ── DOM refs ──────────────────────────────────────────────────────────────
	const treeEl       = document.getElementById( 'wp-docsify-tree' );
	const editorEl     = document.getElementById( 'wp-docsify-editor' );
	const saveBtn      = document.getElementById( 'btn-save' );
	const saveStatus   = document.getElementById( 'wp-docsify-save-status' );
	const currentLabel = document.getElementById( 'wp-docsify-current-file' );
	const placeholder  = document.querySelector( '.wp-docsify-editor-placeholder' );
	const btnNewFile   = document.getElementById( 'btn-new-file' );
	const btnNewFolder = document.getElementById( 'btn-new-folder' );

	// ── AJAX helper ───────────────────────────────────────────────────────────
	function ajax( action, data ) {
		const body = new FormData();
		body.append( 'action', action );
		body.append( 'nonce', cfg.nonce );
		Object.entries( data || {} ).forEach( ( [ k, v ] ) => body.append( k, v ) );

		return fetch( cfg.ajaxUrl, { method: 'POST', body } )
			.then( r => r.json() )
			.then( res => {
				if ( ! res.success ) throw new Error( res.data?.message || 'Error' );
				return res.data;
			} );
	}

	// ── File tree ─────────────────────────────────────────────────────────────
	function loadTree() {
		ajax( 'wp_docsify_get_tree' ).then( items => renderTree( items, treeEl, '' ) );
	}

	function renderTree( items, container, prefix ) {
		container.innerHTML = '';

		if ( ! items.length ) {
			container.innerHTML = '<p class="wp-docsify-tree-empty">' + i18n.noFiles + '</p>';
			return;
		}

		items.forEach( item => buildNode( item, container, prefix ) );
	}

	function buildNode( item, container, prefix ) {
		const isFolder = item.type === 'folder';
		const wrapper  = document.createElement( 'div' );
		wrapper.className = 'wp-docsify-tree-wrapper';

		const row = document.createElement( 'div' );
		row.className = 'wp-docsify-tree-row wp-docsify-tree-' + item.type;
		row.dataset.path = item.path;
		row.dataset.type = item.type;

		const icon = document.createElement( 'span' );
		icon.className = 'wp-docsify-tree-icon';
		icon.textContent = isFolder ? '▶' : '';
		icon.setAttribute( 'aria-hidden', 'true' );

		const name = document.createElement( 'span' );
		name.className = 'wp-docsify-tree-name';
		name.textContent = item.name;

		const actions = document.createElement( 'span' );
		actions.className = 'wp-docsify-tree-actions';
		actions.innerHTML =
			'<button class="wp-docsify-action-btn rename-btn" data-path="' + esc( item.path ) + '" title="Rename" aria-label="Rename">✏</button>' +
			'<button class="wp-docsify-action-btn delete-btn" data-path="' + esc( item.path ) + '" data-type="' + esc( item.type ) + '" title="Delete" aria-label="Delete">✕</button>';

		row.appendChild( icon );
		row.appendChild( name );
		row.appendChild( actions );
		wrapper.appendChild( row );

		if ( isFolder ) {
			const children = document.createElement( 'div' );
			children.className = 'wp-docsify-tree-children';

			if ( item.children && item.children.length ) {
				item.children.forEach( child => buildNode( child, children, item.path ) );
			}

			wrapper.appendChild( children );

			row.addEventListener( 'click', function ( e ) {
				if ( e.target.closest( '.wp-docsify-action-btn' ) ) return;
				const open = row.classList.toggle( 'open' );
				icon.textContent = open ? '▼' : '▶';
				children.style.display = open ? 'block' : 'none';
				selectedFolder = item.path;
			} );

			children.style.display = 'none';
		} else {
			row.addEventListener( 'click', function ( e ) {
				if ( e.target.closest( '.wp-docsify-action-btn' ) ) return;
				openFile( item.path );
			} );
		}

		// Rename
		row.querySelector( '.rename-btn' ).addEventListener( 'click', function ( e ) {
			e.stopPropagation();
			const newName = prompt( i18n.renamePrompt, item.name );
			if ( ! newName || newName === item.name ) return;
			ajax( 'wp_docsify_rename_item', { path: item.path, new_name: newName } )
				.then( () => loadTree() )
				.catch( err => alert( err.message ) );
		} );

		// Delete
		row.querySelector( '.delete-btn' ).addEventListener( 'click', function ( e ) {
			e.stopPropagation();
			if ( ! confirm( i18n.confirmDelete ) ) return;
			const action = isFolder ? 'wp_docsify_delete_folder' : 'wp_docsify_delete_file';
			ajax( action, { path: item.path } ).then( () => {
				if ( currentPath === item.path ) {
					closeEditor();
				}
				loadTree();
			} ).catch( err => alert( err.message ) );
		} );

		container.appendChild( wrapper );
	}

	// ── Editor ────────────────────────────────────────────────────────────────
	function initMDE() {
		if ( mde ) return;
		editorEl.style.display = '';
		mde = new EasyMDE( {
			element:       editorEl,
			spellChecker:  false,
			autosave:      { enabled: false },
			status:        false,
			toolbar: [
				'bold', 'italic', 'heading', '|',
				'unordered-list', 'ordered-list', '|',
				'link', 'image', '|',
				'code', 'quote', 'table', '|',
				'preview', 'side-by-side', 'fullscreen',
			],
		} );

		mde.codemirror.on( 'change', () => setDirty( true ) );
	}

	function openFile( path ) {
		if ( isDirty && ! confirm( i18n.unsavedChanges ) ) return;

		ajax( 'wp_docsify_get_file', { path } ).then( data => {
			initMDE();
			placeholder.style.display = 'none';
			mde.value( data.content );
			currentPath = path;
			setDirty( false );
			currentLabel.textContent = path;
			saveBtn.disabled = false;
			highlightActive( path );
		} ).catch( err => alert( err.message ) );
	}

	function closeEditor() {
		currentPath = null;
		currentLabel.textContent = '';
		saveBtn.disabled = true;
		setDirty( false );
		if ( mde ) mde.value( '' );
		placeholder.style.display = '';
	}

	function saveFile() {
		if ( ! currentPath || ! mde ) return;
		const content = mde.value();

		ajax( 'wp_docsify_save_file', { path: currentPath, content } ).then( () => {
			setDirty( false );
			flashStatus( i18n.saved, 'success' );
		} ).catch( () => flashStatus( i18n.saveError, 'error' ) );
	}

	function setDirty( dirty ) {
		isDirty = dirty;
		document.title = dirty ? '● ' + document.title.replace( /^● /, '' ) : document.title.replace( /^● /, '' );
		if ( ! dirty ) saveStatus.textContent = '';
	}

	function flashStatus( msg, type ) {
		saveStatus.textContent = msg;
		saveStatus.className   = 'wp-docsify-save-status wp-docsify-save-status--' + type;
		setTimeout( () => { saveStatus.textContent = ''; saveStatus.className = 'wp-docsify-save-status'; }, 3000 );
	}

	function highlightActive( path ) {
		document.querySelectorAll( '.wp-docsify-tree-row.active' ).forEach( el => el.classList.remove( 'active' ) );
		const row = document.querySelector( '.wp-docsify-tree-row[data-path="' + esc( path ) + '"]' );
		if ( row ) row.classList.add( 'active' );
	}

	// ── New file / folder ─────────────────────────────────────────────────────
	btnNewFile.addEventListener( 'click', () => {
		const prefix = selectedFolder ? selectedFolder + '/' : '';
		const name   = prompt( i18n.newFileName );
		if ( ! name ) return;

		if ( ! name.endsWith( '.md' ) ) {
			alert( i18n.onlyMd );
			return;
		}

		const path = prefix + name;
		ajax( 'wp_docsify_create_file', { path } )
			.then( () => { loadTree(); openFile( path ); } )
			.catch( err => alert( err.message ) );
	} );

	btnNewFolder.addEventListener( 'click', () => {
		const prefix = selectedFolder ? selectedFolder + '/' : '';
		const name   = prompt( i18n.newFolderName );
		if ( ! name ) return;
		ajax( 'wp_docsify_create_folder', { path: prefix + name } )
			.then( () => loadTree() )
			.catch( err => alert( err.message ) );
	} );

	// ── Save button / Ctrl+S ──────────────────────────────────────────────────
	saveBtn.addEventListener( 'click', saveFile );

	document.addEventListener( 'keydown', function ( e ) {
		if ( ( e.ctrlKey || e.metaKey ) && e.key === 's' ) {
			e.preventDefault();
			saveFile();
		}
	} );

	// Warn on page leave with unsaved changes
	window.addEventListener( 'beforeunload', function ( e ) {
		if ( isDirty ) {
			e.preventDefault();
			e.returnValue = '';
		}
	} );

	// ── Helpers ───────────────────────────────────────────────────────────────
	function esc( str ) {
		return String( str )
			.replace( /&/g, '&amp;' )
			.replace( /"/g, '&quot;' )
			.replace( /</g, '&lt;' )
			.replace( />/g, '&gt;' );
	}

	// ── Init ─────────────────────────────────────────────────────────────────
	loadTree();
} )();
