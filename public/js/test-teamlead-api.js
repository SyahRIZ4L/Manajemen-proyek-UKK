console.log('Testing TeamLead API endpoints...');

// Test function to check API endpoints
async function testTeamLeadAPIs() {
    const endpoints = [
        '/api/teamlead/team-members',
        '/api/teamlead/boards-for-card',
        '/api/teamlead/project-detail',
        '/api/teamlead/project-members'
    ];

    for (const endpoint of endpoints) {
        console.log(`\n=== Testing: ${endpoint} ===`);

        try {
            const response = await fetch(endpoint, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            });

            console.log(`Status: ${response.status}`);

            if (response.ok) {
                const data = await response.json();
                console.log('Response:', data);

                // Specific checks
                if (endpoint === '/api/teamlead/team-members' && data.success) {
                    console.log(`Found ${data.data?.length || 0} team members`);
                } else if (endpoint === '/api/teamlead/boards-for-card' && data.success) {
                    console.log(`Found ${data.data?.length || 0} boards`);
                }
            } else {
                console.error('Error response:', await response.text());
            }
        } catch (error) {
            console.error('Request failed:', error);
        }
    }
}

// Run test when page loads (for browser console)
if (typeof window !== 'undefined') {
    // Wait for DOM to be ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', testTeamLeadAPIs);
    } else {
        testTeamLeadAPIs();
    }
}
