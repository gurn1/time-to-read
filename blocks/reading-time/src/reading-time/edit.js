import { __ } from '@wordpress/i18n';
import { useBlockProps, withColors } from '@wordpress/block-editor';
import { useEffect, useState } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import { select } from '@wordpress/data';

import './editor.scss';

export default function Edit({ attributes }) {
	const {
		style: {
			color: { text: textColor } = {},
			typography: { fontSize } = {},
		} = {},
	} = attributes;

	// const blockProps = useBlockProps({ style: { color: attributes?.textColor } });
	const [readingTime, setReadingTime] = useState(null);
	const [isLoading, setIsLoading] = useState(true);
	const [error, setError] = useState(null);

	// Get the current post ID
	const postId = select('core/editor').getCurrentPostId();

	const blockProps = useBlockProps({
		style: {
			color: textColor || undefined,
			fontSize: fontSize || undefined,
		}
	});

	useEffect(() => {
		if (!postId) return;
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
