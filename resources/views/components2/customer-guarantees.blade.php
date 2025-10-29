{{-- Customer Guarantees Component --}}
{{-- Usage: @include('components.customer-guarantees', ['limit' => 4, 'featured' => true]) --}}

@php
    $limit = $limit ?? 4;
    $featured = $featured ?? false;
    $endpoint = $featured ? '/api/v1/customer-guarantees/featured/featured' : '/api/v1/customer-guarantees';
    $endpoint .= '?limit=' . $limit;
@endphp

<div class="customer-guarantees" id="customer-guarantees">
    <div class="row">
        <div class="col-12">
            <div class="guarantees-container d-flex justify-content-center flex-wrap">
                {{-- Loading state --}}
                <div class="guarantee-loading text-center w-100">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <p class="mt-2">Loading guarantees...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.guarantee-badge {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    margin: 10px;
    text-align: center;
    text-decoration: none;
    color: inherit;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    position: relative;
    overflow: hidden;
}

.guarantee-badge:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    text-decoration: none;
    color: inherit;
}

.guarantee-badge .icon {
    font-size: 24px;
    margin-bottom: 8px;
}

.guarantee-badge .title {
    font-size: 12px;
    font-weight: bold;
    line-height: 1.2;
    padding: 0 5px;
}

.guarantee-badge .description {
    font-size: 10px;
    opacity: 0.9;
    margin-top: 4px;
    padding: 0 5px;
}

.guarantee-loading {
    padding: 40px 0;
}

.guarantees-container {
    min-height: 200px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.querySelector('.guarantees-container');
    const loading = document.querySelector('.guarantee-loading');

    fetch('{{ $endpoint }}')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data) {
                displayGuarantees(data.data);
            } else {
                showError('Failed to load guarantees');
            }
        })
        .catch(error => {
            console.error('Error loading guarantees:', error);
            showError('Error loading guarantees');
        });

    function displayGuarantees(guarantees) {
        loading.style.display = 'none';

        if (guarantees.length === 0) {
            container.innerHTML = '<div class="text-center w-100"><p class="text-muted">No guarantees available</p></div>';
            return;
        }

        const guaranteesHtml = guarantees.map(guarantee => {
            const iconHtml = guarantee.icon_type === 'class'
                ? `<i class="${guarantee.icon}"></i>`
                : guarantee.icon_type === 'image'
                ? `<img src="${guarantee.icon}" alt="${guarantee.title}" style="width: 24px; height: 24px;">`
                : guarantee.icon;

            const linkAttributes = guarantee.has_link
                ? `href="${guarantee.link_url}" ${guarantee.link_new_tab ? 'target="_blank" rel="noopener noreferrer"' : ''}`
                : '';

            const linkTag = guarantee.has_link ? 'a' : 'div';

            return `
                <${linkTag} class="guarantee-badge" ${linkAttributes}
                   style="background-color: ${guarantee.badge_color}; color: ${guarantee.text_color};">
                    <div class="icon">${iconHtml}</div>
                    <div class="title">${guarantee.title}</div>
                    ${guarantee.description ? `<div class="description">${guarantee.description}</div>` : ''}
                </${linkTag}>
            `;
        }).join('');

        container.innerHTML = guaranteesHtml;
    }

    function showError(message) {
        loading.style.display = 'none';
        container.innerHTML = `<div class="text-center w-100"><p class="text-danger">${message}</p></div>`;
    }
});
</script>
