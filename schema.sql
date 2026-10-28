CREATE TABLE IF NOT EXISTS users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(120) NOT NULL,
  username VARCHAR(80) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('admin','employee') NOT NULL DEFAULT 'employee',
  assigned_stage ENUM('printing','binding','cutting','lamination','packing') NULL,
  active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS production_jobs (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  job_code VARCHAR(40) NOT NULL UNIQUE,
  book_title VARCHAR(200) NOT NULL,
  client_name VARCHAR(160) DEFAULT '',
  total_quantity INT UNSIGNED NOT NULL,
  due_date DATE NULL,
  priority ENUM('normal','urgent') NOT NULL DEFAULT 'normal',
  notes TEXT NULL,
  created_by INT UNSIGNED NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_jobs_creator FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS production_updates (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  job_id INT UNSIGNED NOT NULL,
  stage ENUM('printing','binding','cutting','lamination','packing') NOT NULL,
  status ENUM('pending','in_progress','completed') NOT NULL DEFAULT 'pending',
  completed_quantity INT UNSIGNED NOT NULL DEFAULT 0,
  employee_id INT UNSIGNED NULL,
  note TEXT NULL,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq_job_stage (job_id, stage),
  CONSTRAINT fk_updates_job FOREIGN KEY (job_id) REFERENCES production_jobs(id) ON DELETE CASCADE,
  CONSTRAINT fk_updates_employee FOREIGN KEY (employee_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS production_history (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  job_id INT UNSIGNED NOT NULL,
  stage ENUM('printing','binding','cutting','lamination','packing') NOT NULL,
  status ENUM('pending','in_progress','completed') NOT NULL,
  completed_quantity INT UNSIGNED NOT NULL DEFAULT 0,
  note TEXT NULL,
  changed_by INT UNSIGNED NULL,
  changed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_history_job FOREIGN KEY (job_id) REFERENCES production_jobs(id) ON DELETE CASCADE,
  CONSTRAINT fk_history_user FOREIGN KEY (changed_by) REFERENCES users(id) ON DELETE SET NULL,
  INDEX idx_history_job (job_id, changed_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create the first admin through setup.php. Do not put a plain-text password in this file.
