<?php
require '../includes/session.php';
require '../includes/db.php';
require '../includes/sidebar.php';
?>

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

<div class="d-flex">
    <div class="flex-grow-1" style="margin-left: 250px;">
        <div class="container-fluid mt-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="input-group w-50">
                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control" id="searchBar" placeholder="Search members...">
                    <select class="form-select" id="groupFilter" style="max-width: 150px;">
                        <option value="">All groups</option>
                        <option value="officer">Officers</option>
                        <option value="RIC">RIC</option>
                        <option value="FA">FA</option>
                        <option value="4H">4H</option>
                    </select>
                </div>
                <button class="btn btn-outline-primary d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#memberModal">
                    <i class="bi bi-person-plus-fill"></i> Add Member
                </button>
            </div>

            <div class="table-responsive shadow-sm bg-white rounded p-3">
                <table class="table table-hover table-sm align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Address</th>
                            <th>Contact</th>
                            <th>Gender</th>
                            <th>Birthday</th>
                            <th>Group</th>
                            <th class="position-column" style="display: none;">Position</th>
                            <th>Age</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="memberTable">
                        <?php
                        $result = $conn->query("SELECT *, 
                            TIMESTAMPDIFF(YEAR, birthday, CURDATE()) as age,
                            DATE_FORMAT(birthday, '%Y-%m-%d') as formatted_birthday
                            FROM members 
                            ORDER BY name ASC");
                        while ($row = $result->fetch_assoc()):
                        ?>
                            <tr data-role-type="<?= htmlspecialchars($row['role_type']) ?>">
                                <td><?= htmlspecialchars($row['name']) ?></td>
                                <td><?= htmlspecialchars($row['address']) ?></td>
                                <td><?= htmlspecialchars($row['contact_number']) ?></td>
                                <td><?= ucfirst(htmlspecialchars($row['gender'])) ?></td>
                                <td><?= date('M d, Y', strtotime($row['formatted_birthday'])) ?></td>
                                <td><?= htmlspecialchars($row['group_membership']) ?></td>
                                <td class="position-column" style="display: none;"><?= htmlspecialchars($row['officer_position'] ?? '') ?></td>
                                <td><?= $row['age'] ?></td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-outline-warning me-1 editBtn"
                                        data-id="<?= $row['id'] ?>"
                                        data-name="<?= htmlspecialchars($row['name']) ?>"
                                        data-address="<?= htmlspecialchars($row['address']) ?>"
                                        data-contact="<?= htmlspecialchars($row['contact_number']) ?>"
                                        data-gender="<?= htmlspecialchars($row['gender']) ?>"
                                        data-group="<?= htmlspecialchars($row['group_membership']) ?>"
                                        data-birthday="<?= $row['birthday'] ?>"
                                        data-bs-toggle="modal"
                                        data-bs-target="#memberModal">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <a href="../members/delete_member.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Delete this member?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <!-- Member Modal -->
            <div class="modal fade" id="memberModal" tabindex="-1">
                <div class="modal-dialog">
                    <form class="modal-content" method="POST" action="add_member.php" id="memberForm">
                        <div class="modal-header">
                            <h5 class="modal-title">Member</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id" id="memberId">
                            <input type="text" name="name" id="memberName" class="form-control mb-2" placeholder="Name" required>
                            <textarea name="address" id="memberAddress" class="form-control mb-2" placeholder="Address" required></textarea>
                            <div class="input-group mb-2">
                                <span class="input-group-text bg-white">+63</span>
                                <input type="tel" name="contact_number" id="memberContact" class="form-control" 
                                       placeholder="Enter Mobile Number" pattern="[0-9]{10}" maxlength="10" required>
                            </div>
                            
                            <!-- Role Selection -->
                            <div class="btn-group mb-2 w-100" role="group">
                                <input type="radio" class="btn-check" name="role_type" id="memberRole" value="member" checked>
                                <label class="btn btn-outline-primary" for="memberRole">Member</label>
                                
                                <input type="radio" class="btn-check" name="role_type" id="officerRole" value="officer">
                                <label class="btn btn-outline-primary" for="officerRole">Association Officer</label>
                            </div>

                            <!-- Officer Position Dropdown (Hidden by default) -->
                            <select name="officer_position" id="officerPosition" class="form-select mb-2" style="display: none;">
                                <option disabled selected>Select position</option>
                                <option value="President">President</option>
                                <option value="Vice President">Vice President</option>
                                <option value="Secretary">Secretary</option>
                                <option value="Treasurer">Treasurer</option>
                                <option value="Auditor">Auditor</option>
                                <option value="Bookkeeper">Bookkeeper</option>
                            </select>

                            <select name="gender" id="memberGender" class="form-select mb-2" required>
                                <option disabled selected>Select gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                            <select name="group_membership" id="memberGroup" class="form-select mb-2" required>
                                <option disabled selected>Select group membership</option>
                                <option value="RIC">RIC</option>
                                <option value="FA">FA</option>
                                <option value="4H">4H</option>
                            </select>
                            <div class="row mb-2">
                                <div class="col">
                                    <label for="memberBirthday" class="form-label">Birthdate <span class="text-danger">*</span></label>
                                    <input type="date" name="birthday" id="memberBirthday" class="form-control" required 
                                           max="<?= date('Y-m-d') ?>" onchange="calculateAge(this.value)">
                                </div>
                                <div class="col">
                                    <label for="memberAge" class="form-label">Age <span class="text-danger">*</span></label>
                                    <input type="text" id="memberAge" class="form-control" placeholder="Age" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-save"></i> Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap Bundle with Popper (required for modal) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Custom Script -->
<script>
    // Age calculator function
    function calculateAge(birthday) {
        const birthDate = new Date(birthday);
        const today = new Date();
        let age = today.getFullYear() - birthDate.getFullYear();
        const monthDiff = today.getMonth() - birthDate.getMonth();
        
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }
        
        document.getElementById('memberAge').value = age;
    }

    // Update edit button handler to include birthday
    document.querySelectorAll('.editBtn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.getElementById('memberForm').action = 'edit_member.php';
            document.getElementById('memberId').value = btn.dataset.id;
            document.getElementById('memberName').value = btn.dataset.name;
            document.getElementById('memberAddress').value = btn.dataset.address;
            document.getElementById('memberContact').value = btn.dataset.contact;
            document.getElementById('memberGender').value = btn.dataset.gender;
            document.getElementById('memberGroup').value = btn.dataset.group;
            document.getElementById('memberBirthday').value = btn.dataset.birthday;
            calculateAge(btn.dataset.birthday);
        });
    });

    // Reset form when modal is closed
    const memberModal = document.getElementById('memberModal');
    memberModal.addEventListener('hidden.bs.modal', function () {
        document.getElementById('memberForm').reset();
        document.getElementById('memberForm').action = 'add_member.php';
        document.getElementById('memberId').value = '';
        // Reset all dropdowns to first option
        document.getElementById('memberProduct').selectedIndex = 0;
        document.getElementById('memberGender').selectedIndex = 0;
        document.getElementById('memberGroup').selectedIndex = 0;
        document.getElementById('memberAge').value = '';
    });

    // Single edit button handler (remove the duplicate one at the bottom)
    document.querySelectorAll('.editBtn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.getElementById('memberForm').action = 'edit_member.php';
            document.getElementById('memberId').value = btn.dataset.id;
            document.getElementById('memberName').value = btn.dataset.name;
            document.getElementById('memberAddress').value = btn.dataset.address;
            document.getElementById('memberContact').value = btn.dataset.contact;
            
            // Fix for dropdown labels
            const genderValue = btn.dataset.gender;
            const groupValue = btn.dataset.group;
            const genderSelect = document.getElementById('memberGender');
            const groupSelect = document.getElementById('memberGroup');
            
            if (genderValue) {
                genderSelect.value = genderValue;
            } else {
                genderSelect.selectedIndex = 0;
            }
            
            if (groupValue) {
                groupSelect.value = groupValue;
            } else {
                groupSelect.selectedIndex = 0;
            }
            
            document.getElementById('memberBirthday').value = btn.dataset.birthday;
            calculateAge(btn.dataset.birthday);
        });
    });

    // Single edit button handler
    document.querySelectorAll('.editBtn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.getElementById('memberForm').action = 'edit_member.php';
            document.getElementById('memberId').value = btn.dataset.id;
            document.getElementById('memberName').value = btn.dataset.name;
            document.getElementById('memberAddress').value = btn.dataset.address;
            document.getElementById('memberContact').value = btn.dataset.contact;
            document.getElementById('memberGender').value = btn.dataset.gender;
            
            // Handle role type and officer position
            const row = btn.closest('tr');
            const roleType = row.dataset.roleType;
            const officerPosition = row.querySelector('.position-column').textContent;
            
            // Set the correct radio button
            if (roleType === 'officer') {
                document.getElementById('officerRole').checked = true;
                document.getElementById('officerPosition').style.display = 'block';
                document.getElementById('officerPosition').value = officerPosition;
                document.getElementById('officerPosition').required = true;
            } else {
                document.getElementById('memberRole').checked = true;
                document.getElementById('officerPosition').style.display = 'none';
                document.getElementById('officerPosition').required = false;
            }
            
            // Set other fields
            const groupSelect = document.getElementById('memberGroup');
            const groupValue = btn.dataset.group;
            groupSelect.value = groupValue;
            
            document.getElementById('memberBirthday').value = btn.dataset.birthday;
            calculateAge(btn.dataset.birthday);
        });
    });

    // Search filter
    document.getElementById('searchBar').addEventListener('input', function () {
        const filter = this.value.toLowerCase();
        document.querySelectorAll('#memberTable tr').forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(filter) ? '' : 'none';
        });
    });

    // Group filter
    document.getElementById('groupFilter').addEventListener('change', function() {
        const groupFilter = this.value.toLowerCase();
        const searchFilter = document.getElementById('searchBar').value.toLowerCase();
        const positionColumns = document.querySelectorAll('.position-column');
        const rows = Array.from(document.querySelectorAll('#memberTable tr'));
        
        // Show/hide position column
        positionColumns.forEach(col => {
            col.style.display = groupFilter === 'officer' ? 'table-cell' : 'none';
        });
        
        // Sort rows based on filter selection
        if (groupFilter === 'officer') {
            // Sort by position order for officers
            rows.sort((a, b) => {
                const posA = a.querySelector('.position-column').textContent;
                const posB = b.querySelector('.position-column').textContent;
                const positions = ['President', 'Vice President', 'Secretary', 'Treasurer', 'Auditor', 'Bookkeeper'];
                return positions.indexOf(posA) - positions.indexOf(posB);
            });
        } else {
            // Sort alphabetically by name for other filters
            rows.sort((a, b) => {
                const nameA = a.children[0].textContent.toLowerCase();
                const nameB = b.children[0].textContent.toLowerCase();
                return nameA.localeCompare(nameB);
            });
        }
        
        // Apply filters and reorder
        rows.forEach(row => {
            let match = true;
            if (groupFilter === 'officer') {
                match = row.dataset.roleType === 'officer';
            } else if (groupFilter) {
                match = row.children[5].textContent.toLowerCase() === groupFilter;
            }
            const searchMatch = row.textContent.toLowerCase().includes(searchFilter);
            row.style.display = (match && searchMatch) ? '' : 'none';
            
            // Reorder in table
            if (match && searchMatch) {
                row.parentNode.appendChild(row);
            }
        });
    });

    // Update edit button handler to include group membership
    document.querySelectorAll('.editBtn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.getElementById('memberForm').action = 'edit_member.php';
            document.getElementById('memberId').value = btn.dataset.id;
            document.getElementById('memberName').value = btn.dataset.name;
            document.getElementById('memberAddress').value = btn.dataset.address;
            document.getElementById('memberContact').value = btn.dataset.contact;
            document.getElementById('memberProduct').value = btn.dataset.product;
            document.getElementById('memberGender').value = btn.dataset.gender;
            document.getElementById('memberBirthday').value = btn.dataset.birthday;
            calculateAge(btn.dataset.birthday);
        });
    });

    // Add this new function for name validation
    function validateNameInput(input) {
        // Allow letters, spaces, dots, commas, hyphens, and apostrophes
        const validPattern = /[^a-zA-ZñÑ\s.,'"-]/g;
        let value = input.value.replace(validPattern, '');
        input.value = value;
    }

    // Add event listener to name input
    document.getElementById('memberName').addEventListener('input', function() {
        validateNameInput(this);
    });

    // Handle officer position dropdown visibility
    function toggleOfficerPosition(isOfficer) {
        const officerPosition = document.getElementById('officerPosition');
        if (isOfficer) {
            officerPosition.style.display = 'block';
            officerPosition.required = true;
        } else {
            officerPosition.style.display = 'none';
            officerPosition.required = false;
            officerPosition.selectedIndex = 0;
        }
    }

    // Update the role type radio button event listeners
    document.querySelectorAll('input[name="role_type"]').forEach(radio => {
        radio.addEventListener('change', function() {
            toggleOfficerPosition(this.value === 'officer');
        });
    });

    // Update the modal reset handler
    memberModal.addEventListener('hidden.bs.modal', function () {
        document.getElementById('memberForm').reset();
        document.getElementById('memberForm').action = 'add_member.php';
        document.getElementById('memberId').value = '';
        // Reset all dropdowns to first option
        document.getElementById('memberGender').selectedIndex = 0;
        document.getElementById('memberGroup').selectedIndex = 0;
        document.getElementById('memberAge').value = '';
        // Reset role type and officer position
        document.getElementById('memberRole').checked = true;
        toggleOfficerPosition(false);
    });

    // Contact number handling
    function formatContactNumber(input) {
        // Remove any non-numeric characters
        let value = input.value.replace(/\D/g, '');
        
        // Remove leading '63' or '0' if present
        if (value.startsWith('63')) {
            value = value.substring(2);
        } else if (value.startsWith('0')) {
            value = value.substring(1);
        }
        
        // Ensure max length of 10 digits
        value = value.substring(0, 10);
        
        // Update input value
        input.value = value;
        
        return value;
    }

    // Add event listener to contact input
    document.getElementById('memberContact').addEventListener('input', function() {
        formatContactNumber(this);
    });

    // Modify the edit button handler to format contact number
    document.querySelectorAll('.editBtn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.getElementById('memberForm').action = 'edit_member.php';
            document.getElementById('memberId').value = btn.dataset.id;
            document.getElementById('memberName').value = btn.dataset.name;
            document.getElementById('memberAddress').value = btn.dataset.address;
            document.getElementById('memberContact').value = btn.dataset.contact;
            
            // Fix for dropdown labels
            const genderValue = btn.dataset.gender;
            const groupValue = btn.dataset.group;
            const genderSelect = document.getElementById('memberGender');
            const groupSelect = document.getElementById('memberGroup');
            
            if (genderValue) {
                genderSelect.value = genderValue;
            } else {
                genderSelect.selectedIndex = 0;
            }
            
            if (groupValue) {
                groupSelect.value = groupValue;
            } else {
                groupSelect.selectedIndex = 0;
            }
            
            document.getElementById('memberBirthday').value = btn.dataset.birthday;
            calculateAge(btn.dataset.birthday);
        });
    });

    // Single edit button handler
    document.querySelectorAll('.editBtn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.getElementById('memberForm').action = 'edit_member.php';
            document.getElementById('memberId').value = btn.dataset.id;
            document.getElementById('memberName').value = btn.dataset.name;
            document.getElementById('memberAddress').value = btn.dataset.address;
            const contactInput = document.getElementById('memberContact');
            let contactValue = btn.dataset.contact;
            if (contactValue.startsWith('+63')) {
                contactValue = contactValue.substring(3);
            }
            contactInput.value = contactValue;
            
            document.getElementById('memberGender').value = btn.dataset.gender;
            
            // Handle role type and officer position
            const row = btn.closest('tr');
            const roleType = row.dataset.roleType;
            const officerPosition = row.querySelector('.position-column').textContent;
            
            // Set the correct radio button
            if (roleType === 'officer') {
                document.getElementById('officerRole').checked = true;
                document.getElementById('officerPosition').style.display = 'block';
                document.getElementById('officerPosition').value = officerPosition;
                document.getElementById('officerPosition').required = true;
            } else {
                document.getElementById('memberRole').checked = true;
                document.getElementById('officerPosition').style.display = 'none';
                document.getElementById('officerPosition').required = false;
            }
            
            // Set other fields
            const groupSelect = document.getElementById('memberGroup');
            const groupValue = btn.dataset.group;
            groupSelect.value = groupValue;
            
            document.getElementById('memberBirthday').value = btn.dataset.birthday;
            calculateAge(btn.dataset.birthday);
        });
    });

    // Add form submit handler to format number
    document.getElementById('memberForm').addEventListener('submit', function(e) {
        const contactInput = document.getElementById('memberContact');
        const formattedNumber = '+63' + formatContactNumber(contactInput);
        contactInput.value = formattedNumber;
    });
</script>

</body>
</html>
