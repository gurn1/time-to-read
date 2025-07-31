import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { useEffect, useState } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import { select } from '@wordpress/data';

import './editor.scss';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @return {Element} Element to render.
 */
export default function Edit(attributes, setAttributes) {
	const blockProps = useBlockProps({ style: { color: attributes?.textColor } });
	const [readingTime, setReadingTime] = useState(null);
	const [isLoading, setIsLoading] = useState(true);
	const [error, setError] = useState(null);

	// Get the current post ID
	const postId = select('core/editor').getCurrentPostId();

	useEffect(() => {
		if (!postId) return;
console.log('attributes 2', attributes);
		apiFetch({ path: `/time-to-read/v1/${postId}` })
			.then((result) => {
				setReadingTime(result || 'Not available');
				setIsLoading(false);
			})
			.catch((err) => {
				setError(__('Failed to fetch reading time', 'reading-time'));
				setIsLoading(false);
			});
	}, [postId]);

	return (
      <div { ...blockProps }>
		{ isLoading && __('Loading reading time…', 'reading-time') }
		{ error && <span>{ error }</span> }
		{ !isLoading && !error && (
			<div dangerouslySetInnerHTML={{ __html: readingTime }} />
		) }
	  </div>
	);
}
